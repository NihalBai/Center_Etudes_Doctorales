<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Demande;
use App\Models\These;
use App\Models\Doctorant;
use App\Models\Membre;
use App\Models\DemandeSession;
use Carbon\Carbon;
use App\Services\DocumentService;

class DemandeController extends Controller
{ 
    protected $documentService;

    public function index()
    {
        $demandes = DB::table('demandes')
            ->join('theses', 'demandes.id_these', '=', 'theses.id')
            ->join('doctorants', 'theses.id_doctorant', '=', 'doctorants.id')
            ->leftJoin('demande_sessions', 'demandes.id_session', '=', 'demande_sessions.id') // Using leftJoin to make the session join optional
            ->select(
                'demandes.*', 
                'doctorants.nom',
                'doctorants.prenom',
                'theses.id',
                'demande_sessions.date as session' // Selecting the session date
            )
            ->get();
    
        return view('demandes.index', compact('demandes'));
    }
    


    public function create()
    {
        $theseId = session('id_these');   
    
        // Vérifier si l'ID de la thèse est présent dans la session
        if (!$theseId) {
            // Gérer le cas où l'ID de la thèse n'est pas présent dans la session
            return redirect()->back()->withErrors(['error' => 'ID de thèse introuvable dans la session.']);
        }
    
        // Récupérer la thèse correspondant à l'ID
        try {
            $these = These::findOrFail($theseId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Gérer le cas où la thèse correspondant à l'ID n'est pas trouvée
            return redirect()->back()->withErrors(['error' => 'Thèse introuvable.']);
        }
        // Récupérer la dernière session ajoutée
            $lastSession = DemandeSession::latest()->first();

    // Passer la thèse et la dernière session à la vue
    return view('demandes.create', compact('these', 'lastSession'));
}
    

    

public function store(Request $request)
{
    // Récupérer l'ID de la thèse depuis la session
    $theseId = session('id_these');
    
    // Valider les données du formulaire
    $request->validate([
        'formation' => 'required|string|in:MPA,RNES,MI',
        'date' => 'required|date',
        'etat' => 'required|string|in:Refusée,Acceptée,En attente',
    ]);

    // Obtenir le prochain num pour la formation donnée
    $formation = $request->formation;
    $nextNum = Demande::where('formation', $formation)->max('num') + 1;

    // Créer la demande de soutenance
    $demande = Demande::create([
        'formation' => $formation,
        'num' => $nextNum,
        'date' => $request->date,
        'etat' => $request->etat,
        'id_these' => $theseId
    ]);

    // Si une nouvelle session est créée
    if ($request->filled('new_session_date')) {
        // Vérifier s'il existe déjà une session avec cette date
        $existingSession = DemandeSession::where('date', $request->new_session_date)->first();

        if ($existingSession) {
            // Mettre à jour les demandes qui pointaient vers l'ancienne session
            $dem = Demande::where('id_session', $existingSession->id);

            // Supprimer la session existante
            $existingSession->delete();
        }

        // Créer une nouvelle session
        $session = DemandeSession::create([
            'date' => $request->new_session_date,
        ]);

        // Mettre à jour les demandes qui pointaient vers l'ancienne session pour pointer vers la nouvelle session
        if (isset($dem)) {
            $dem->update(['id_session' => $session->id]);
        }

        // Mettre à jour la demande avec l'id de la nouvelle session créée
        $demande->id_session = $session->id;
        $demande->save();
    } elseif ($request->filled('demande_session_id')) {
        // Utiliser la session existante sélectionnée
        $session = DemandeSession::findOrFail($request->demande_session_id);
        // Mettre à jour la demande avec l'id de la session existante sélectionnée
        $demande->id_session = $session->id;
        $demande->save();
    } else {
        // Gérer le cas où aucun choix de session n'est fait
        return redirect()->back()->withErrors(['error' => 'Veuillez choisir ou créer une session.']);
    }

    return redirect()->back()->with('success', 'Demande de soutenance créée avec succès!');
}
    

    public function show($id)
    {
        $demande = Demande::findOrFail($id);
        return view('demandes.show', compact('demande'));
    }

    public function edit($id)
    {
        // Récupérer la demande avec l'ID spécifié
        $demande = Demande::findOrFail($id);
        // Récupérer les informations sur la thèse et le doctorant associés à la demande
        $these = These::join('doctorants', 'theses.id_doctorant', '=', 'doctorants.id')
                      ->where('theses.id', '=', $demande->id_these)
                      ->select('theses.*', 'doctorants.nom', 'doctorants.prenom')
                      ->first();   
        // Récupérer la session associée à la demande
        $sessiondemande = DemandeSession::find($demande->id_session);
        $doctorants = Doctorant::select('id','CINE', 'nom', 'prenom')->get();    
        // Récupérer toutes les sessions (pour les afficher dans un dropdown par exemple)
        $sessions = DemandeSession::all();    
        return view('demandes.edit', compact('demande', 'these', 'sessiondemande', 'sessions','doctorants'));
    }  

    public function update(Request $request, $id)
{  
    // Valider les données du formulaire
    $validated = $request->validate([
        'formation' => 'nullable|string|in:MPA,RNES,MI',
        'date' => 'nullable|date',
        'etat' => 'nullable|string|in:Refusée,Acceptée,En attente',
        'demande_session_id' => 'nullable|exists:demande_sessions,id',
        'new_session_date' => 'nullable|date',
    ]);
 
    try {
        // Trouver la demande existante par son ID
        $demande = Demande::findOrFail($id);

        // Vérifier si la formation a changé
        if ($demande->formation != $validated['formation']) {
            // Obtenir le prochain numéro pour la nouvelle formation
            $nextNum = Demande::where('formation', $validated['formation'])->max('num') + 1;
            $demande->num = $nextNum;
        }

        // Si une nouvelle session doit être créée
        if ($request->filled('new_session') && $request->new_session == true) {
            $newSessionDate = Carbon::parse($request->new_session_date)->format('Y-m-d');

            // Créer une nouvelle session
            $newSession = DemandeSession::create([
                'date' => $newSessionDate,
                // D'autres champs de session peuvent être ajoutés ici
            ]);
            $demande->id_session = $newSession->id;
        } elseif ($request->filled('demande_session_id')) {
            // Utiliser la session existante sélectionnée
            $session = DemandeSession::findOrFail($request->demande_session_id);
            $demande->id_session = $session->id;
        } else {
            // Gérer le cas où aucun choix de session n'est fait
            return redirect()->back()->withErrors(['error' => 'Veuillez choisir ou créer une session.']);
        }

        // Mettre à jour les données de la demande
        $demande->formation = $validated['formation'];
        $demande->date = $validated['date'];
        $demande->etat = $validated['etat'];
        $demande->num = $demande->num;
        $demande->save();

        return redirect()->route('demandes.edit', ['demande' => $demande->id])->with('success', 'Demande de soutenance mise à jour avec succès.');

    } catch (\Exception $e) {
        // Gérer les erreurs éventuelles
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}



    public function destroy($id)
    {
        $demande = Demande::findOrFail($id);
        $demande->delete();
        return redirect()->route('demandes.index')->with('success', 'Demande de soutenance supprimée avec succès.');
    }


    //
    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    //DownLaod Demande de soutenance de these
    public function downloadDemande($id)
    {
        // Requête SQL pour récupérer les données nécessaires
        $demande = DB::table('demandes')
            ->join('theses', 'demandes.id_these', '=', 'theses.id')
            ->join('doctorants', 'theses.id_doctorant', '=', 'doctorants.id')
            ->join('membres', 'doctorants.id_encadrant', '=', 'membres.id')
            ->join('demande_sessions', 'demandes.id_session', '=', 'demande_sessions.id')
            ->select(
                'demandes.id',
                'demandes.formation as form',
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
                'membres.prenom as encadrant_prenom',
                'demande_sessions.date as session'
            )
            ->where('demandes.id', $id)
            ->first();
    
        if (!$demande) {
            abort(404, 'Demande non trouvée');
        }
    
        // Formater la date au format dd-mm-yyyy
        $demande->date_premiere_inscription = date('d-m-Y', strtotime($demande->date_premiere_inscription));
        $demande->date = date('d-m-Y', strtotime($demande->date));

        // Générer le document
        $documentPath = $this->documentService->generateDemandeSoutenance($demande);
    
        // Télécharger le document généré
        return response()->download(storage_path('app/' . $documentPath))->deleteFileAfterSend(true);
    }
    
    
}