<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\DemandeSession;
use App\Models\Doctorant;
use App\Models\File;
use App\Models\These;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\DocumentService;
use App\Jobs\GenerateDemandeSoutenance;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CommissionController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index()
    {
        $demandes = DB::table('demandes')
            ->join('theses', 'demandes.id_these', '=', 'theses.id')
            ->join('doctorants', 'theses.id_doctorant', '=', 'doctorants.id')
            ->select('demandes.*','demandes.formation as form', 'doctorants.nom', 'doctorants.prenom', 'theses.*')
            ->get();

        $sessions = DemandeSession::all();

        return view('commissions.index', compact('demandes', 'sessions'));
    }
//liste de commissions
    public function commissions()
   {
     // Récupérer toutes les demandes avec leurs sessions associées
    $demandes = Demande::with('session')->get();

    // Calculer le pourcentage d'avis favorables pour chaque session
    $sessions = DemandeSession::with(['demande', 'demande.these.doctorant.encadrant'])
        ->orderBy('created_at', 'desc') // Tri par date de création, LIFO
        ->get()
        ->map(function ($session) {
            $totalDemandes = $session->demande->count();
            $avisFavorables = $session->demande->where('etat', 'Acceptée')->count();
            $pourcentageAvisFavorables = ($totalDemandes > 0) ? ($avisFavorables / $totalDemandes) * 100 : 0;

            // Récupérer les doctorants avec avis favorable
            $doctorants = $session->demande->where('etat', 'Acceptée')->map(function ($demande) {
                return $demande->these->doctorant;
            });

            return [
                'session' => $session,
                'pourcentage' => round($pourcentageAvisFavorables, 2),
                'doctorants' => $doctorants,
            ];
        });

    return view('commissions.commissions', compact('demandes', 'sessions'));
     }


     public function create()
     { 
         // Get the list of sessions
         $sessions = DemandeSession::all();
         // Get the list of doctorants with their CINE, name, and surname who have a demande
         $doctorants = Doctorant::whereHas('these.demande', function($query) {
             $query->whereNotNull('id');
         })->select('id', 'CINE', 'nom', 'prenom')->get();
         return view('commissions.create', compact('sessions', 'doctorants'));
     }
     

    public function store(Request $request)
    { 
        try {
            // Validation des données
            $request->validate([
                'new_session_date' => 'required|date',
                'pv_global_signe' => 'required|file',
                'doctorant_ids' => 'required|array',
                'doctorant_ids.*' => 'exists:doctorants,id',
                'individual_pvs.*' => 'required|file',
                'avis.*' => 'required|string|in:Acceptée,Refusée',
            ]); 
    
            // Vérification de l'existence d'une session avec la même date
            $existingSession = DemandeSession::where('date', $request->new_session_date)->first();
            if ($existingSession) {
                return redirect()->back()->with('error', 'Une commission existe déjà pour cette date. Veuillez choisir une autre date.');
            }
    
            // Création de la nouvelle session
            $session = DemandeSession::create([
                'date' => $request->new_session_date,
            ]);
    
            // Téléchargement du PV global
            if ($request->hasFile('pv_global_signe')) {
                $file = $request->file('pv_global_signe');
                $uuid = (string) Str::uuid();
                $filename = $uuid . '.' . $file->getClientOriginalExtension();
                $pvGlobalPath = $file->storeAs('public/pv_global_signe', $filename);
                $session->update(['pv_global_signe' => $pvGlobalPath]);
            }
    
            // Traitement des doctorants sélectionnés
            foreach ($request->doctorant_ids as $doctorantId) {
                $doctorant = Doctorant::findOrFail($doctorantId);
                $pvIndividuel = $request->file('individual_pvs')[$doctorantId];
                $avis = $request->avis[$doctorantId];
    
                // Stockage du PV individuel
                $foldername = $doctorant->id . '-' . ' (' . $doctorant->prenom . ' ' . $doctorant->nom . ')';
                $foldername = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $foldername); // Assurez un nom de dossier sûr en conservant les points
                $directory = 'doctorants/' . $foldername; // Utilisation du chemin relatif à storage/app/public
    
                // Vérification et création du répertoire si nécessaire
                if (!Storage::exists('public/' . $directory)) {
                    Storage::makeDirectory('public/' . $directory, 0755, true);
                }
    
                // Stocker le dossier dans l'entité Doctorant
                $doctorant->dossier = $directory;
                $doctorant->save();
    
                // Vérification de l'existence du document pour le doctorant
                $existingFile = File::where('doctorant_id', $doctorantId)
                                    ->where('type', 'pv_individuel')
                                    ->first();
    
                if ($existingFile) {
                    // Si un document du même type existe déjà, on le supprime
                    Storage::delete('public/' . $existingFile->path); // Suppression du fichier
                    $existingFile->delete(); // Suppression de l'entrée en base de données
                }
    
                // Génération d'un nom unique pour le fichier incluant le type de document
                $uniqueName = uniqid() . '_pv_individuel_' . $pvIndividuel->getClientOriginalName();
    
                // Stockage du fichier dans le dossier spécifique du doctorant
                $path = $pvIndividuel->storeAs('public/' . $directory, $uniqueName);
    
                if ($path) {
                    // Création d'une nouvelle entrée en base de données pour le fichier
                    File::create([
                        'type' => 'pv_individuel', // Type de document
                        'path' => str_replace('public/', '', $path), // Chemin relatif du fichier
                        'doctorant_id' => $doctorantId, // ID du doctorant
                    ]);
                }
      
                // Mise à jour de l'état de la demande via la relation Thèse
                $these = These::where('id_doctorant', $doctorantId)->first(); 
                if ($these) {
                    $demande = Demande::where('id_these', $these->id)->first();
                    if ($demande) {
                        $demande->update(['etat' => $avis, 'id_session' => $session->id]);
                    }
                }
               
            }
    
            return redirect()->route('commissions.create')->with('success', 'Commission ajoutée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
    
    
    public function edit($id)
    {
        // Retrieve the commission by ID
        $commission = DemandeSession::findOrFail($id);

        // Retrieve sessions for the dropdown
        $sessions = DemandeSession::all(); // Replace with your actual session retrieval logic

        return view('commissions.edit', compact('commission', 'sessions'));
    }
   

    public function update(Request $request, DemandeSession $commission)
    {
        // Valider les données de la requête
        $request->validate([
            'new_session_date' => [
                'nullable',
                'date',
                // Vérifier que la nouvelle session date est unique
                Rule::unique('demande_sessions', 'date')->ignore($commission->id),
            ],
            'pv_global_signe' => 'nullable|file', // Exemple de validation pour le fichier PDF
        ]);

        try {
            // Utiliser une transaction pour garantir l'intégrité des données
            DB::beginTransaction();

            // Mettre à jour les champs de la commission
            if ($request->has('new_session_date')) {
                // Mettre à jour la date de la nouvelle session si elle est définie
                $commission->date = $request->input('new_session_date');
            }

              // Téléchargement du PV global
 
if ($request->hasFile('pv_global_signe')) {
    $file = $request->file('pv_global_signe');
    $uuid = Str::uuid(); // Génère un UUID
    $filename = $uuid . '.' . $file->getClientOriginalExtension(); // Nom du fichier avec l'extension d'origine
    $pvGlobalPath = $file->storeAs('pv_global_signe', $filename, 'public'); // Stockage du fichier avec l'UUID comme nom

    $commission->pv_global_signe = $pvGlobalPath; // Mise à jour du chemin dans la base de données
}
            // Sauvegarder la commission mise à jour
            $commission->save();

            // Confirmer la transaction
            DB::commit();

            // Rediriger avec un message de succès
            return redirect()->back()->with('success', 'La commission a été mise à jour avec succès.');
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();

            // Rediriger avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la commission.');
        }
    }

    public function searchBySession(Request $request)
    {
        $sessionId = $request->input('session');

        $demandes = DB::table('demandes')
            ->join('theses', 'demandes.id_these', '=', 'theses.id')
            ->join('doctorants', 'theses.id_doctorant', '=', 'doctorants.id')
            ->select('demandes.*', 'doctorants.nom', 'doctorants.prenom', 'theses.*')
            ->when($sessionId, function ($query, $sessionId) {
                return $query->where('id_session', $sessionId);
            })
            ->get();

        $sessions = DemandeSession::all();

        return view('commissions.index', compact('demandes', 'sessions'));
    }

    public function searchByEtat(Request $request)
    {
        $etat = $request->input('etat');

        $demandes = Demande::select('demandes.*', 'doctorants.nom', 'doctorants.prenom')
        ->join('theses', 'demandes.id_these', '=', 'theses.id')
        ->join('doctorants', 'theses.id_doctorant', '=', 'doctorants.id')
        ->when($etat, function ($query, $etat) {
            return $query->where('demandes.etat', $etat);
        })
        ->get();

        $sessions = DemandeSession::all();

        return view('commissions.index', compact('demandes', 'sessions'));
    }

    public function downloadSelected(Request $request)
    {
        $demandeIds = $request->input('demandes');

        if (empty($demandeIds)) {
            return response()->json(['error' => 'Aucune demande sélectionnée.'], 400);
        }

        $demandes = Demande::whereIn('id', explode(',', $demandeIds))->get();

        if ($demandes->isEmpty()) {
            return response()->json(['error' => 'Aucune demande trouvée.'], 404);
        }

        $downloadUrls = [];

        foreach ($demandes as $demande) {
            // Fetch demand data
            $demandeData = $this->fetchDemandeData($demande);

            // Dispatch the job with demand data and DocumentService instance
            $job = new GenerateDemandeSoutenance($demandeData, $this->documentService);
            $documentPath = $job->handle();

            // Generate the URL to download the file
            $downloadUrl = route('commissions.downloadFile', ['path' => $documentPath]);
            $downloadUrls[] = $downloadUrl;
                   }

        return response()->json(['downloadUrls' => $downloadUrls]);
    }
    /**
     * Récupère les données de la demande pour la génération de document.
     *
     * @param  Demande  $demande
     * @return mixed
     */
    protected function fetchDemandeData(Demande $demande)
    {
        return Demande::join('theses', 'demandes.id_these', '=', 'theses.id')
            ->join('doctorants', 'theses.id_doctorant', '=', 'doctorants.id')
            ->join('membres', 'doctorants.id_encadrant', '=', 'membres.id')
            ->select(
                'demandes.id',
                'demandes.RNES',
                'demandes.date',
                'theses.titreOriginal as titre',
                'theses.structure_recherche',
                'theses.formation',
                'theses.date_premiere_inscription',
                'theses.nombre_publications_article',
                'theses.nombre_publications_conference',
                'theses.nombre_communications',
                'doctorants.nom as doctorant_nom',
                'doctorants.prenom as doctorant_prenom',
                'membres.nom as encadrant_nom',
                'membres.prenom as encadrant_prenom'
            )
            ->where('demandes.id', $demande->id)
            ->first();
    }

    /**
     * Télécharge le fichier généré.
     *
     * @param  string  $path
     * @return \Illuminate\Http\Response
     */
    public function downloadFile($path)
    {
        // Assuming $path is something like 'mz-amina_demande.docx'
        $filePath = storage_path('app/public/demandes/' . $path);
    
        if (!Storage::disk('public')->exists('demandes/' . $path)) {
            abort(404, 'File not found.');
        }
    
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
    // Génération du PV global par session saisie par l'utilisateur
    public function generatePVGlobal(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:demande_sessions,id',
        ]);

        $sessionId = $request->session_id;

        try {
            $outputPath = $this->documentService->generatePVGlobal($sessionId);
            return response()->download(storage_path('app/' . $outputPath))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    } 

public function showIndividualPVs($idSession)
{
    // Récupérer la session
    $session = DemandeSession::findOrFail($idSession);
    // Récupérer les theses associées à cette session via les demandes
    $demandes = Demande::where('id_session', $idSession)->get();
    $thesesIds = $demandes->pluck('id_these');
    $doctorantsIds = These::whereIn('id', $thesesIds)->pluck('id_doctorant');
    // Récupérer les doctorants associés aux theses trouvées
    $doctorants = Doctorant::whereIn('id', $doctorantsIds)->get();
    // Récupérer les PVs individuels pour les doctorants de cette session
    $pvs = File::whereIn('doctorant_id', $doctorants->pluck('id'))->where('type', 'pv_individuel')->get();
    // Utiliser compact pour passer les variables à la vue
    return view('commissions.individualPVs', compact('session', 'doctorants', 'pvs','demandes'));
}

public function updatepvindividuel(Request $request, $session_id, $doctorant_id)
{    
    $request->validate([
        'avis' => 'nullable|in:Acceptée,Refusée', // Validation de l'avis
        'pv_file' => 'nullable|file', // Validation du fichier PV (max 10MB)
        'doctorant_id' => 'required|exists:doctorants,id',
    ]);
    // Récupérer le doctorant avec les relations nécessaires
    $doctorant = Doctorant::with('these.demande')->findOrFail($doctorant_id);
    $session = DemandeSession::findOrFail($session_id);

    // Récupérer l'état de la demande associée à la thèse du doctorant
    $demande = $doctorant->these->demande->first();

    // Mettre à jour l'état de la demande et l'ID de session
    $demande->update([
        'etat' => $request->avis,
        'id_session' => $session->id,
    ]);

    // Stockage du PV individuel
    if ($request->hasFile('pv_file')) {
        // Stockage du PV individuel dans un dossier spécifique
        $foldername = $doctorant->id .'-'. ' (' . $doctorant->prenom . ' ' . $doctorant->nom . ')';
        $foldername = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $foldername); // Assurez un nom de dossier sûr en conservant les points
        $directory = 'doctorants/' . $foldername; // Utilisation du chemin relatif à storage/app

        // Vérification et création du répertoire si nécessaire
        if (!Storage::exists('public/' . $directory)) {
            Storage::makeDirectory('public/' . $directory, 0755, true);
        }

        // Suppression du PV précédent s'il existe
        $existingFile = File::where('doctorant_id', $doctorant_id)
                            ->where('type', 'pv_individuel')
                            ->first();

        if ($existingFile) {
            Storage::delete($existingFile->path); // Suppression du fichier
            $existingFile->delete(); // Suppression de l'entrée en base de données
        }

        // Génération d'un nom unique pour le fichier incluant le type de document
        $uniqueName = uniqid() . '_pv_individuel_' . $request->file('pv_file')->getClientOriginalName();

        // Stockage du fichier dans le dossier spécifique du doctorant
        $path = $request->file('pv_file')->storeAs('public/' . $directory, $uniqueName);

        // Création d'une nouvelle entrée en base de données pour le fichier
        File::create([
            'type' => 'pv_individuel', // Type de document
            'path' => str_replace('public/', '', $path), // Chemin unique du fichier
            'doctorant_id' => $doctorant_id, // ID du doctorant
        ]);
    }

    return redirect()->back()->with('success', 'Le PV a été mise à jour avec succès.');
}









}
