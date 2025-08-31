<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Membre;
use App\Models\Affiliation;


use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Doctorant;
use App\Models\User;
use App\Models\These;
use App\Models\Evaluer;
use App\Models\Scolarite;
use Illuminate\Support\Facades\File as IlluminateFile;
use App\Models\File;
use Illuminate\Support\Facades\Validator;

use  App\Models\Soutenance;


class DoctorantController extends Controller
{
    


    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string', // Allow the search parameter to be nullable and a string
        ]);
    
        $search = $request->input('search');
    
        // Define an array of searchable fields
        $searchableFields = ['nom', 'prenom', 'email', 'sex', 'tele', 'speciality'];
    
        $query = doctorant::with('membre');
    
        if ($search) {
            $query->where(function ($query) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $query->orWhere($field, 'LIKE', "%$search%");
                }
               // Search within the member's name
                $query->orWhereHas('member', function ($query) use ($search) {
                    $query->where('nom', 'LIKE', "%$search%")
                          ->orWhere('prenom', 'LIKE', "%$search%");
                });
            });
        }
    
        // Get the search results
        $doctorants = $query->get();
    
        return view('doctorants.index', ['doctorants' => $doctorants]);
    }
    
    public function showDoctorantsWithResults()
    {
        $doctorants = Doctorant::whereHas('soutenances.resultat')
            ->with(['soutenances.resultat', 'encadrant.membreArabe'])
            ->get();

        return view('diplom', compact('doctorants'));
    }


    public function redirectToAppropriatePage($id)
    {
        $doctorant = Doctorant::with(['soutenances.evaluer.membre'])->findOrFail($id);

    // Check if the doctorant has soutenances
    if ($doctorant->soutenances->isNotEmpty()) {
        $hasArabicInfo = false;

        // Iterate over soutenances to check if all members of the jury have Arabic member information
        foreach ($doctorant->soutenances as $soutenance) {
            foreach ($soutenance->evaluer as $evaluer) {
                if ($evaluer->membre->membreArabe) { // Accessing membreArabe through membre relationship
                    $hasArabicInfo = true;
                } else {
                    $hasArabicInfo = false;
                    break; // If any member doesn't have Arabic information, break the loop
                }
            }
            if (!$hasArabicInfo) {
                break; // If any soutenance doesn't have Arabic information, break the loop
            }
        }

        // Redirect based on the presence of Arabic information for all members of the jury
        if ($hasArabicInfo) {
            // Redirect to final-page if all members have Arabic information
            return redirect()->route('final-page', ['id' => $id]);
        } else {
            // If any member of the jury doesn't have Arabic information, redirect to autre-information
            $id_soutenance = $doctorant->soutenances->first()->id;
            return redirect()->route('autre-information', ['id_soutenance' => $id_soutenance]);
        }
    }  
    }
    
    public function index1($id)
    {
        // Retrieve the $soutenance variable from your database or other source
        $soutenance = Soutenance::find($id); // Replace $id with the ID of the Soutenance you want to retrieve

        // Pass the $soutenance variable to your view
        return view('autre-information', compact('soutenance'));
    }

    //PART 1


    public function index()
    {
        $doctorants = Doctorant::all();
        return view('doctorants.index', compact('doctorants'));
    }

    // public function create()
    // {
    //     $encadrants = Membre::all();
        
    //     return view('doctorants.create', compact('encadrants'));
    // }
    public function create()
    {
        $encadrants = Membre::all();
        
        // List of months in French
        $months = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];
        
        return view('doctorants.create', compact('encadrants', 'months'));
    }


    //les fichiers associes au doctorant 
    public function createFile()
    {
        // Récupérer l'ID du doctorant depuis la session
        $doctorant_id = session('doctorant_id');
        try {
            $doctorant = Doctorant::findOrFail($doctorant_id);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Gérer le cas où la thèse correspondant à l'ID n'est pas trouvée
            return redirect()->back()->withErrors(['error' => 'Doctorant introuvable.']);
        }
        // 

        // Passer l'ID du doctorant à la vue files.create
        return view('files.create', compact('doctorant'));
    }



    public function store(Request $request)
    { 
        try {
            // Valider les données du formulaire de doctorant
            $validatedData = $request->validate([
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'CINE' => 'required|string|unique:doctorants,CINE',
                'sex' => 'required|in:male,female',
                'date_de_naissance' => 'required|date',
                'lieu' => 'required|string',
                'id_encadrant' => 'required|integer',
                'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'email' => 'required|string|email|unique:doctorants,email',
                'tele' => 'required|string|max:255',
                'discipline' => 'required|string',
                'scolarites' => 'required|array',
                'scolarites.*.niveau' => 'required|string',
                'scolarites.*.specialite' => 'required|string',
                'scolarites.*.mois' => 'required|string',
                'scolarites.*.annee' => 'required|integer',
                'scolarites.*.mention' => 'required|string',
            ]);

            // Gestion du téléchargement de la photo
            if ($request->hasFile('photo_path') && $request->file('photo_path')->isValid()) {
                $fileName = uniqid() . '.' . $request->file('photo_path')->getClientOriginalExtension();
                $path=$request->file('photo_path')->storeAs('public/photosdoctorants', $fileName);
                $validatedData['photo_path'] = $fileName;
            }

            // Création du doctorant
            $doctorant = Doctorant::create([
                'nom' => $validatedData['nom'],
                'prenom' => $validatedData['prenom'],
                'CINE' => $validatedData['CINE'],
                'sex' => $validatedData['sex'],
                'date_de_naissance' => $validatedData['date_de_naissance'],
                'id_encadrant' => $validatedData['id_encadrant'],
                'photo_path' => $validatedData['photo_path'] ?? null,
                'email' => $validatedData['email'],
                'tele' => $validatedData['tele'],
                'dossier' => 'null',
                'discipline' => $validatedData['discipline'],
                'lieu' => $validatedData['lieu'],
            ]);

            // Stocker l'ID du doctorant dans la session
            session(['doctorant_id' => $doctorant->id]);

                    // Vérifier l'unicité des niveaux de scolarité
        foreach ($validatedData['scolarites'] as $scolarite) {
            $existingScolarite = Scolarite::where('id_doctorant', $doctorant->id)
                ->where('niveau', $scolarite['niveau'])
                ->first();

            if ($existingScolarite) {
                // Retourner un message d'erreur
                return redirect()->back()->withErrors(['scolarites.*.niveau' => 'Le niveau ' . $scolarite['niveau'] . ' existe déjà pour ce doctorant.']);
            }
        }

            // Enregistrement des scolarités du doctorant
            foreach ($validatedData['scolarites'] as $scolarite) {
                Scolarite::create([
                    'id_doctorant' => $doctorant->id,
                    'niveau' => $scolarite['niveau'],
                    'specialite' => $scolarite['specialite'],
                    'annee' => $scolarite['annee'],
                    'mois' => $scolarite['mois'],
                    'mention' => $scolarite['mention'],
                ]);
            }

            // Redirection vers la création des fichiers avec un message de succès
            return redirect()->route('files.create', compact('doctorant'))->with('success', 'Doctorant crée avec succès');
        } catch (\Exception $e) {
            // Gestion des exceptions
            return back()->withError($e->getMessage());
        }
    }
  

    public function storeFile(Request $request)
    {
        // Récupération de l'ID du doctorant depuis la session
        $doctorantId = $request->input('doctorant_id');

        // Validation des données de la requête
        $request->validate([
            'documents.*' => 'required|file',
        ]);

        // Récupération des fichiers uploadés
        $documents = $request->file('documents');

        // Récupération du doctorant
        $doctorant = Doctorant::findOrFail($doctorantId);

        // Création du dossier spécifique pour le doctorant
        $foldername = $doctorant->id . '-' . ' (' . $doctorant->prenom . ' ' . $doctorant->nom . ')';
        $foldername = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $foldername); // Assurez un nom de dossier sûr en conservant les points
        $directory = 'doctorants/' . $foldername; // Utilisation du chemin relatif à storage/app

        // Vérification et création du répertoire si nécessaire
    if (!Storage::exists('public/' . $directory)) {
        Storage::makeDirectory('public/' . $directory, 0755, true);
    }
        // Stocker le dossier dans l'entité Doctorant
        $doctorant->dossier = $directory;
        $doctorant->save();

        // Parcours de chaque document pour le traitement
        foreach ($documents as $documentType => $file) {
            // Vérification de l'existence du document pour le doctorant
            $existingFile = File::where('doctorant_id', $doctorantId)
                                ->where('type', $documentType)
                                ->first();

            if ($existingFile) {
                // Si un document du même type existe déjà, redirection avec message d'erreur
                return redirect()->back()->with('error', 'Un document de type ' . $documentType . ' existe déjà pour ce doctorant.');
            }

            // Récupération du nom original du fichier
            $originalName = $file->getClientOriginalName();

            // Génération d'un nom unique pour le fichier incluant le type de document
            $uniqueName = uniqid() . '' . $documentType . '' . $originalName;

            // Stockage du fichier dans le dossier spécifique du doctorant
            $path = $file->storeAs('public/' . $directory, $uniqueName);

    
            if ($path) {
                // Création d'une nouvelle entrée en base de données pour le fichier
                File::create([
                    'type' => $documentType, // Type de document
                    'path' => str_replace('public/', '', $path), // Chemin sans le préfixe public/
                    'doctorant_id' => $doctorantId, // ID du doctorant
                ]);
            }
        }

        // Redirection avec message de succès
        return redirect()->back()->with('success', 'Documents ajoutés avec succès!');
    }
    


    public function update(Request $request, Doctorant $doctorant)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'nom' => 'nullable|string',
            'prenom' => 'nullable|string',
            'CINE' => 'nullable|string',
            'sex' => 'nullable|in:male,female',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'date_de_naissance' => 'nullable|date',
            'dossierType' => 'nullable|string', // Type de document
            'dossier' => 'nullable|file', // Validation du dossier
            'id_encadrant' => 'nullable|integer',
            'old_photo_path' => 'nullable|string',
            'old_dossier' => 'nullable|string',
            'email' => 'nullable|string|email',
            'tele' => 'nullable|string|max:255',
            'lieu' => 'nullable|string',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Handle photo upload
            if ($request->hasFile('photo_path') && $request->file('photo_path')->isValid()) {
                // Delete old photo if it exists
                if ($validatedData['old_photo_path']) {
                    Storage::delete('public/photosdoctorants/' . $validatedData['old_photo_path']);
                }

                // Save the new photo
                $fileName = uniqid() . '.' . $request->file('photo_path')->getClientOriginalExtension();
                $path = $request->file('photo_path')->storeAs('public/photosdoctorants', $fileName);

                if (empty($path)) {
                    throw new \Exception('Une erreur est survenue lors de l\'enregistrement de la photo.');
                }

                $validatedData['photo_path'] = $fileName;
            } else {
                $validatedData['photo_path'] = $validatedData['old_photo_path'];
            }

            // Handle document upload based on dossierType
            if ($request->hasFile('dossier') && $request->file('dossier')->isValid()) {
                // Delete old document if it exists
                if ($validatedData['old_dossier']) {
                    Storage::delete('public/' . $validatedData['old_dossier']);
                }

                // Create specific folder for the doctoral student
                $foldername = $doctorant->id . '-' . '(' . $doctorant->prenom . ' ' . $doctorant->nom . ')';
                $foldername = preg_replace('/[^A-Za-z0-9\-. ()]/', '', $foldername); // Ensure a safe folder name while retaining periods
                $directory = 'doctorants/' . $foldername; // Use the relative path to storage/app/public

                // Check and create the directory if necessary
                if (!Storage::exists('public/' . $directory)) {
                    Storage::makeDirectory('public/' . $directory, 0755, true);
                }

                // Store the document in the Doctorant entity
                $doctorant->dossier = $directory;
                $doctorant->save();

                $originalName = $request->file('dossier')->getClientOriginalName();
                // Generate a unique name for the file including the document type
                $uniqueName = uniqid() . '' . $validatedData['dossierType'] . '' . $originalName;
                // Store the file in the specific folder for the doctoral student
            $path =$request->file('dossier')->storeAs('public/' . $directory, $uniqueName);
                if (empty($path)) {
                    throw new \Exception('Une erreur est survenue lors de l\'enregistrement du document!');
                }

                // Find existing files associated with this doctoral student and this type of dossier
                $file = File::where('doctorant_id', $doctorant->id)
                            ->where('type', $validatedData['dossierType'])
                            ->first();

                if ($file) {
                    // Update the existing file
                    $file->path = str_replace('public/', '', $path); // Remove 'public/' from the path
                    $file->updated_at = now();
                    $file->save();
                } else {
                    // Create a new file if it does not exist
                    File::create([
                        'doctorant_id' => $doctorant->id,
                        'type' => $validatedData['dossierType'],
                        'path' => str_replace('public/', '', $path), // Remove 'public/' from the path
                    ]);
                }
            } else {
                // If no new document is uploaded, use the existing value
                $validatedData['dossier'] = $validatedData['old_dossier'];
            }

            // Update the doctoral student with the validated data
            $doctorant->update([
                'nom' => $validatedData['nom'],
                'prenom' => $validatedData['prenom'],
                'sex' => $validatedData['sex'],
                'CINE' => $validatedData['CINE'],
                'photo_path' => $validatedData['photo_path'],
                'date_de_naissance' => $validatedData['date_de_naissance'],
                'id_encadrant' => $validatedData['id_encadrant'],
                'email' => $validatedData['email'],
                'tele' => $validatedData['tele'],
                'lieu' => $validatedData['lieu'],
            ]);

            // Commit the transaction
            DB::commit();

            // Redirect with a success message
            return redirect()->back()->with('success', 'Doctorant modifié avec succès!');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Redirect with an error message
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // public function profil($id)
    // {
    //     // Récupérer les informations du doctorant à partir de son ID, en chargeant également les relations 'these' et 'encadrant'
    //     $doctorant = Doctorant::with('these', 'encadrant', 'files')->find($id);
        
    //     // Récupérer tous les encadrants
    //     $encadrants = Membre::all();

    //     // Récupérer les niveaux de scolarité du doctorant et ajouter la conversion des mentions
    //     $scolarites = Scolarite::where('id_doctorant', $id)->get()->map(function ($scolarite) {
    //         $scolarite->mention_label = $this->getMentionLabel($scolarite->mention);
    //         return $scolarite;
    //     });

    //     return view('doctorants.profil', compact('doctorant', 'encadrants', 'scolarites'));
    // }




    public function updateScolarite(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'niveau' => 'required|string',
            'mois' => 'required|string',
            'annee' => 'required|string',
            'specialite' => 'required|string',
            'mention' => 'required|string|in:passable,a_bien,bien,tr_bien,excellent',
            'doctorant_id' => 'required|integer',
            'scolarite_id' => 'nullable|integer',  // Ajouté pour permettre un identifiant optionnel
            'parcours' => 'nullable|string' // Ajouté pour vérifier le parcours
        ]);

        try {
            // Vérifier l'unicité de la scolarité pour le doctorant
            $existingScolarite = Scolarite::where('id_doctorant', $validatedData['doctorant_id'])
                ->where('niveau', $validatedData['niveau'])
                ->where('mois', $validatedData['mois'])
                ->where('annee', $validatedData['annee'])
                ->where('specialite', $validatedData['specialite'])
                ->where('mention', $validatedData['mention'])
                ->first();

            if ($existingScolarite && (!$validatedData['scolarite_id'] || $existingScolarite->id != $validatedData['scolarite_id'])) {
                return redirect()->back()->with('error', 'Ce parcours est déjà renseigné.');
            }

            // Si le parcours est "autre", créer une nouvelle scolarité
            if ($request->parcours === 'autre') {
                Scolarite::create([
                    'id_doctorant' => $validatedData['doctorant_id'],
                    'niveau' => $validatedData['niveau'],
                    'mois' => $validatedData['mois'],
                    'annee' => $validatedData['annee'],
                    'specialite' => $validatedData['specialite'],
                    'mention' => $validatedData['mention'],
                ]);

                return redirect()->back()->with('success', 'Nouvelle scolarité ajoutée avec succès.');
            }

            // Si un ID de scolarité est fourni, mettre à jour la scolarité existante
            if (!empty($validatedData['scolarite_id'])) {
                $scolarite = Scolarite::findOrFail($validatedData['scolarite_id']);

                // Mettre à jour les champs de la scolarité
                $scolarite->niveau = $validatedData['niveau'];
                $scolarite->mois = $validatedData['mois'];
                $scolarite->annee = $validatedData['annee'];
                $scolarite->specialite = $validatedData['specialite'];
                $scolarite->mention = $validatedData['mention'];

                // Sauvegarder la scolarité mise à jour
                $scolarite->save();

                return redirect()->back()->with('success', 'Les informations de scolarité ont été mises à jour avec succès.');
            }

            // Si aucun ID de scolarité n'est fourni et que le parcours n'est pas "autre", traiter cela comme une erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la scolarité.');

        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la scolarité.');
        }
    }
  
//====================generation des pvs individuels sous format PDF==============================================================


    public function profil($id)
    {
        // Récupérer les informations du doctorant à partir de son ID, en chargeant également les relations 'these' et 'encadrant'
        $doctorant = Doctorant::with('these', 'encadrant', 'files')->find($id);
        
        // Récupérer tous les encadrants
        $encadrants = Membre::all();

        // Récupérer les niveaux de scolarité du doctorant et ajouter la conversion des mentions
        $scolarites = Scolarite::where('id_doctorant', $id)->get()->map(function ($scolarite) {
            $scolarite->mention_label = $this->getMentionLabel($scolarite->mention);
            return $scolarite;
        });

        return view('doctorants.profil', compact('doctorant', 'encadrants', 'scolarites'));
    }

    private function getMentionLabel($mentionValue)
    {
        switch ($mentionValue) {
            case 'passable':
                return 'Passable';
            case 'a_bien':
                return 'Assez Bien';
            case 'bien':
                return 'Bien';
            case 'tr_bien':
                return 'Très Bien';
            case 'excellent':
                return 'Excellent';
            default:
                return '';
        }
    }










    

    
}
