<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Actions\Fortify\UpdateUserPassword;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Actions\Jetstream\DeleteUser;
use Laravel\Fortify\Actions\LogoutOtherBrowserSessions;
use Laravel\Fortify\Actions\DestroyCurrentBrowserSession;

use Illuminate\Support\Facades\DB;

use App\Models\Doctorant;
use App\Models\These;
use App\Models\Membre;
use App\Models\File;
use App\Models\Demande;
use App\Models\Valider;


class UserController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:admin')->only(['destroy', 'updateEtat', 'index1', 'update', 'index','search','create','store','showAutreInformation']);
    //     $this->middleware('role:service_ced')->only(['show', 'update', 'index','search','create','store','showAutreInformation']);
    // }
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'pass' => 'nullable|string',
        'type' => 'nullable|string|in:admin,service_ced,directeur,rapporteur', 
        'sex' => 'nullable|in:male,female',
        'tele' => 'nullable|string',
        'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    ]);

    $userData = $request->only(['name', 'email', 'password', 'type', 'sex', 'tele', 'pass']);
    if ($request->hasFile('profile_photo_path')) {
        $imagePath = $request->file('profile_photo_path')->store('public/images');
        // Adjust the path to remove 'public/' as it's automatically included in the storage
        $imagePath = str_replace('public/', '', $imagePath);
        $userData['profile_photo_path'] = $imagePath;
    }

    $user = User::create($userData);

    // Assign role based on the type
    $role = Role::where('name', $request->type)->first();

    if ($role) {
        $user->roles()->attach($role);
    }

    return redirect()->back()->with('success', 'Utilisateur créé avec succès');
}



    public function show(User $user)
    {
        return view('user.profile1', compact('user'));
    }


    
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
       
        // Pass the $user variable to the view
        return view('user.profile1', compact('user'));
    }




public function update(Request $request, User $user)
{
    $request->validate([
        'email' => 'required|email|unique:users,email,' . $user->id,
        'tele' => 'nullable|string',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $userData = $request->only(['email', 'tele']);

    // Check if there's an uploaded profile photo
    if ($request->hasFile('profile_photo')) {
        $imagePath = $request->file('profile_photo')->store('public/images');
        // Adjust the path to remove 'public/' as it's automatically included in the storage
        $imagePath = str_replace('public/', '', $imagePath);
        $userData['profile_photo_path'] = $imagePath;
    }

    $user->update($userData);

    return redirect()->back()->with('success', 'Profil mis à jour avec succès');
}


public function updatePassword(Request $request, UpdateUserPassword $updater)
{
    // Retrieve the authenticated user
    $user = Auth::user();

    // Check if the user exists
    if (!$user) {
        return redirect()->back()->with('error', 'Utilisateur introuvable');
    }

    // Call the update method in the UpdateUserPassword action class
    $updater->update($user, $request->all());

    // Redirect back with success message
    return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès.');
}
    public function showResetForm(Request $request)
    {
        return view('user.profile1')->with(['request' => $request]);
    }


       public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    // public function dashboard()
    // {
    //     // Get the authenticated user
    //     $user = Auth::user();

    //     // Determine the layout based on user's role
    //     $layout = 'layouts.layoutDefault'; // Default layout
    //     if ($user->role === 'admin') {
    //         $layout = 'layouts.layoutAdmin';
    //     } elseif ($user->role === 'directeur') {
    //         $layout = 'layouts.layoutDirecteur';
    //     }

    //     // Return the view with the specified layout
    //     return view('dashboard', ['layout' => $layout]);

    // }

    public function index1()
    {
        $users = User::all();
        return view('comptes', compact('users')); 
    }

    protected $deleteUser;

    

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->deleteUser->delete($user);
        
        // Return a response indicating success
        return response()->json(['success' => true]);
    }

public function updateEtat(Request $request, $id)
{
    $utilisateur = User::find($id);
    $utilisateur->etat = $request->etat;
    $utilisateur->save();
    return response()->json(['success' => true, 'etat' => $utilisateur->etat]);
}

public function login(Request $request)
{
    // Validate the user's login credentials
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // Check if the user's account is active
        if (Auth::user()->etat !== 'active') {
            // If the account is inactive, logout the user and redirect with a message
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }

         // If the account is active, proceed with the login
         $role = Auth::user()->role; // Assuming there's a 'role' attribute in your user model
         switch ($role) {
             case 'admin':
                 return redirect()->route('dashboard');
                 
             case 'service_ced':
                 return redirect()->route('service_ced.dashboard');
                
             case 'directeur':
                 return redirect()->route('directeur.dashboard');
               
             case 'rapporteur':
                 return redirect()->route('rapporteur.dashboard');
                 
             default:
                 return redirect()->route('login')->with('error', 'Unknown role.');
         
     }
    }

    // If the authentication fails, redirect back with an error message
    return redirect()->route('login')->with('error', 'Invalid credentials.');
}

        public function assignRole(Request $request, User $user, Role $role)
        {
            $user->assignRole($role);

            return redirect()->back()->with('success', 'Role assigned successfully.');
        }

        public function manageAccount($userId)
        {
            $user1 = User::findOrFail($userId);
            return view('manage_account', compact('user1'));
        }
        public function updateuser(Request $request, $userId)
        {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $userId, // Ignore unique rule for this user
                'password' => 'nullable|min:6|confirmed',
            ]);

            // Find the user by ID
            $user = User::findOrFail($userId);

            // Update the user's name and email
            $user->name = $request->input('name');
            $user->email = $request->input('email');

            // Update the user's password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }
           
            // Save the updated user
            $user->save();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Utilisateur mis à jour avec succès');
        }


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////




        public function profilRapporteur()
        {
            // Récupérer l'utilisateur connecté
            $user = auth()->user();

            // Vérifier si l'utilisateur est connecté
            if (!$user) {
                // Rediriger vers la page de connexion
                return view('lo2');
            }

        

            // Vérifier si l'utilisateur est bien un rapporteur
            if ($user->type !== 'rapporteur') {
                return view('user.profile1', compact('user')); 
            }

            // Si l'utilisateur est un rapporteur, afficher la vue du profil du rapporteur
            return view('rapporteurs.profil', compact('user'));
        }


        public function createRapporteur()
        {   $cineDoctorants = Doctorant::pluck('CINE');
            $membres=Membre::all();
            return view('rapporteurs.create',compact('cineDoctorants','membres'));
        }
        // Méthode pour traiter le formulaire d'inscription des rapporteurs
        public function storeRapporteur(Request $request)
        {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'selectedMembers' => 'required|array',
                'selectedMembers.*' => 'exists:membres,id'
            ]);
        
            // Process each selected member
            foreach ($validatedData['selectedMembers'] as $memberId) {
                $member = Membre::find($memberId);
                if ($member) {
                    // Check if there is already a user with this email
                    $existingUser = User::where('email', $member->email)->first();
                    if ($existingUser) {
                        // If user exists, update the existing user and add to 'valider' table
                        $existingUser->update([
                            'tele' => $member->tele,
                            'sex' => $member->sex,
                            'type' => 'rapporteur'
                        ]);
                        Valider::create([
                            'avis' => null,
                            'lien_rapport' => null,
                            'id_utilisateur' => $existingUser->id,
                            'id_membre' => $member->id,
                            'status' => null,
                            'id_these' => null
                        ]);
                    } else {
                        // Generate a random password
                        $password = bin2hex(random_bytes(4));
        
                        // Create a new user for the member
                        $user = User::create([
                            'name' => $member->prenom . ' ' . $member->nom, // Concatenate prenom and nom to form the full name
                            'email' => $member->email,
                            'tele' => $member->tele,
                            'sex' => $member->sex,
                            'pass' => $password, // Temporary plain password, save as is in the 'pass' column
                            'password' => Hash::make($password), // Hashed password for security, not stored in 'pass'
                            'type' => 'rapporteur',
                            'profile_photo_path' => null
                        ]);
        
                        Valider::create([
                            'avis' => 'accepté',
                            'lien_rapport' => null,
                            'id_utilisateur' => $user->id,
                            'id_membre' => $member->id,
                            'status' => null,
                            'id_these' => null
                        ]);
                    }
                }
            }
        
            // Redirect back with success message
            return redirect()->back()->with('success', 'Rapporteurs ajoutés avec succès!');
        }
        
        public function ajouterDocuments()
        {
            // Récupérer les cines des doctorants ayant cv null ou sans rapport_these ou dossier_scientifique
            $cines = Doctorant::all()->pluck('cine');

            // Récupérer les 3 derniers rapporteurs ajoutés avec la même date de création

        // Premièrement, obtenir la date de création du dernier rapporteur ajouté
        $lastCreated = User::where('type', 'rapporteur')
        ->orderBy('created_at', 'desc')
        ->value('created_at');

        // Ensuite, récupérer les trois derniers rapporteurs ajoutés avec la même date
        $rapporteurs = User::where('type', 'rapporteur')
        ->whereDate('created_at', '=', $lastCreated->toDateString())
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

        // Assurez-vous que $lastCreated existe avant d'accéder à toDateString()
        if ($lastCreated) {
        $rapporteurs = User::where('type', 'rapporteur')
        ->whereDate('created_at', '=', $lastCreated->toDateString())
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();
        } else {
        // Récupérer les 3 derniers rapporteurs ajoutés
        $rapporteurs = User::where('type', 'rapporteur')
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();
        }

            
        $these = DB::table('theses')
        ->select('theses.titreOriginal', 'theses.id_doctorant', 'doctorants.nom', 'doctorants.prenom','doctorants.CINE','theses.id')
        ->join('doctorants', 'theses.id_doctorant', '=', 'doctorants.id')
        ->orderBy('theses.created_at', 'desc')
        ->first();

        $availableRapporteurs=User::where('type', 'rapporteur')
                                    ->get();

            return view('rapporteurs.ajouterDocuments', compact('cines', 'rapporteurs','these','availableRapporteurs'));
        }






    //Importer les documents CV+rapport+dossier scientifique+ la meilleure fonction
    public function joindreDocuments(Request $request)
    {       
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'rapporteur_ids' => 'required|array',
            'rapporteur_ids.*' => 'exists:users,id',
            'id_doctorant' => 'required|exists:theses,id_doctorant',
            'id_these' => 'required|exists:theses,id',
        ], [
            'rapporteur_ids.required' => 'Veuillez sélectionner au moins un rapporteur.',
            'rapporteur_ids.*.exists' => 'Le rapporteur sélectionné est invalide.',
            'id_doctorant.required' => 'L\'ID du doctorant est requis.',
            'id_doctorant.exists' => 'Le doctorant sélectionné est invalide.',
            'id_these.required' => 'L\'ID de la thèse est requis.',
            'id_these.exists' => 'La thèse sélectionnée est invalide.',
        ]);
        
        // Récupérer les informations du formulaire
        $theseId = $validatedData['id_these'];
        $doctorantId = $validatedData['id_doctorant'];

        // Boucle sur les rapporteurs sélectionnés
        foreach ($validatedData['rapporteur_ids'] as $rapporteurId) {
            try {
                // Vérifier si le rapporteur a déjà validé cette thèse
                $validationExistante = DB::table('valider')
                    ->where('id_utilisateur', $rapporteurId)
                    ->where('id_these', $theseId)
                    ->exists();

                if ($validationExistante) {
                    // Si une validation existe déjà, passer au rapporteur suivant
                    continue;
                }

                // Insérer dans la table 'valider'
                DB::table('valider')->insert([
                    'id_membre' => null, // Clé étrangère de l'utilisateur
                    'id_these' => $theseId, // Clé étrangère de la thèse
                    'avis' => 'accepté', // Exemple d'avis, vous pouvez ajuster selon votre logique
                    'lien_rapport' => null, // Lien vers le rapport, s'il y a lieu
                    'id_utilisateur' => $rapporteurId, // ID de l'utilisateur authentifié (le rapporteur)
                    'created_at' => now(), // Date de création
                    'updated_at' => now(), // Date de mise à jour
                ]);
            } catch (\Exception $e) {
                // En cas d'erreur, rediriger avec un message d'erreur
                return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement des données: ' . $e->getMessage());
            }
        }

        // Redirection avec un message de succès
        return redirect()->route('rapporteurs.ajouterDocuments')->with('success', 'Documents ajoutés avec succès pour les rapporteurs sélectionnés.');
    }




    public function indexCompte()
    {
        // Récupérer l'utilisateur authentifié
        $rapporteur = Auth::user();

        // Vérifier si l'utilisateur est bien un rapporteur
        if ($rapporteur->type !== 'rapporteur') {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer toutes les thèses assignées à ce rapporteur via la table valider
        $theses = $rapporteur->theses()->with('doctorant')->get();

        $data = [];

        foreach ($theses as $these) {
            $doctorantId = $these->id_doctorant;

            // Récupérer les fichiers liés au doctorant et au type spécifique
            $rapportThese = File::where('doctorant_id', $doctorantId)->where('type', 'rapport_these')->first();
            $cv = File::where('doctorant_id', $doctorantId)->where('type', 'cv')->first();
            $dossierScientifique = File::where('doctorant_id', $doctorantId)->where('type', 'dossier_scientifique')->first();

            // Récupérer la session via la table 'demandes'
            $demande = Demande::where('id_these', $these->id)->with('session')->first();
            $sessionDate = $demande ? $demande->session->date : null;

            // Construire les données de la thèse et du doctorant
            $data[] = [
                'these' => [
                    'id' => $these->id,
                    'titreOriginal' => $these->titreOriginal,
                    'nom' => $these->doctorant->nom,
                    'prenom' => $these->doctorant->prenom,
                    'cine'=>$these->doctorant->CINE,
                    'date_de_naissance'=>$these->doctorant->date_de_naissance,
                    'encadrant_nom'=>$these->doctorant->encadrant->nom,
                    'encadrant_prenom'=>$these->doctorant->encadrant->prenom,
                    'encadrant_email'=>$these->doctorant->encadrant->email,
                    'email'=>$these->doctorant->email,
                    'structure_recherche' => $these->structure_recherche,
                    'date_premiere_inscription' => $these->date_premiere_inscription,
                    'nombre_publications_article' => $these->nombre_publications_article,
                    'nombre_publications_conference' => $these->nombre_publications_conference,
                    'nombre_communications' => $these->nombre_communications,
                    'formation'=> $these->formation,
                    'session_date' => $sessionDate, // Ajouter la date de la session ici
                ],
                'documents' => [
                    'rapportThese' => $rapportThese ?  $rapportThese->path: null,
                    'cv' => $cv ?  $cv->path : null,
                    'dossierScientifique' => $dossierScientifique ?  $dossierScientifique->path: null,
                ]
            ];
        }

    


        // Retourner les données pour affichage
        return view('rapporteurs.compte', compact('rapporteur', 'data'));
    }



        
        





        //Telechargement des rapports de these +cv +dossier scientifique ========================test
        public function upload(Request $request)
        {
            $request->validate([
                'rapport' => 'required|file',
            ]);
        //verifier
            $fichier = $request->file('rapport');
            $nomFichier = $fichier->getClientOriginalName();
        //placer dans public
            $fichier->storeAs('public/rapports', $nomFichier);
        
            // Retournez une réponse avec le lien de téléchargement du fichier 
            return view('rapporteurs.upload', compact('nomFichier'));
        }




        //Compte du rapporteur
        public function compte($id)
        {
        $rapporteur = User::findOrFail($id); // Assurez-vous d'importer le modèle Rapporteur
    // Récupérer toutes les thèses assignées à ce rapporteur via la table valider
    $theses = These::paginate(1);
    $theses = $rapporteur->theses()->with('doctorant')->get();

    $data = [];

    foreach ($theses as $these) {
        $doctorantId = $these->id_doctorant;

        // Récupérer les fichiers liés au doctorant et au type spécifique
        $rapportThese = File::where('doctorant_id', $doctorantId)->where('type', 'rapport_these')->first();
        $cv = File::where('doctorant_id', $doctorantId)->where('type', 'cv')->first();
        $dossierScientifique = File::where('doctorant_id', $doctorantId)->where('type', 'dossier_scientifique')->first();

        // Récupérer la session via la table 'demandes'
        $demande = Demande::where('id_these', $these->id)->with('session')->first();
        $sessionDate = $demande ? $demande->session->date : null;

        // Construire les données de la thèse et du doctorant
        $data[] = [
            'these' => [
                'id' => $these->id,
                'titreOriginal' => $these->titreOriginal,
                'nom' => $these->doctorant->nom,
                'prenom' => $these->doctorant->prenom,
                'cine'=>$these->doctorant->CINE,
                'date_de_naissance'=>$these->doctorant->date_de_naissance,
                'encadrant_nom'=>$these->doctorant->encadrant->nom,
                'encadrant_prenom'=>$these->doctorant->encadrant->prenom,
                'encadrant_email'=>$these->doctorant->encadrant->email,
                'email'=>$these->doctorant->email,
                'structure_recherche' => $these->structure_recherche,
                'date_premiere_inscription' => $these->date_premiere_inscription,
                'nombre_publications_article' => $these->nombre_publications_article,
                'nombre_publications_conference' => $these->nombre_publications_conference,
                'nombre_communications' => $these->nombre_communications,
                'formation'=> $these->formation,
                'session_date' => $sessionDate, // Ajouter la date de la session ici
            ],
            'documents' => [
                'rapportThese' => $rapportThese ?  $rapportThese->path: null,
                'cv' => $cv ?  $cv->path : null,
                'dossierScientifique' => $dossierScientifique ?  $dossierScientifique->path: null,
            ]
        ];

        
    }

    // Retourner les données pour affichage
    return view('rapporteurs.compte', compact('rapporteur', 'data','theses'));
    }

    //Soumettre le formulaire rapport+avis
    public function submitRapport(Request $request)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'rapport' => 'required|file', // Validez le fichier rapport
            'avis' => 'required|in:accepté,refusé', // Validez l'avis
            'rapporteur_id' => 'required|exists:users,id', // Validez l'ID du rapporteur
            'id_these' => 'required|exists:theses,id' // Validez l'ID de la thèse
        ]);

        try {
            // Traitez le fichier rapport
            $filePath = null;
            if ($request->hasFile('rapport')) {
                $file = $request->file('rapport');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('rapports', $fileName, 'public'); // Stockez le fichier dans le dossier de stockage
            }

            // Récupérez les identifiants du rapporteur et de la thèse depuis les champs cachés
            $rapporteurId = $validatedData['rapporteur_id'];
            $theseId = $validatedData['id_these'];

            // Chercher dans la table correspondant au rapporteur et à la thèse
            $validation = Valider::where('id_utilisateur', $rapporteurId)
                                ->where('id_these', $theseId)
                                ->first();

            if ($validation) {
                // Mettez à jour la ligne avec les nouvelles données
                $validation->update([
                    'avis' => $validatedData['avis'],
                    'lien_rapport' => $filePath,
                ]);

                // Redirigez l'utilisateur avec un message de succès
                return redirect()->back()->with('success', 'Votre rapport a été soumis avec succès!');
            } else {
                // Si aucune ligne trouvée, renvoyez une erreur
                return redirect()->back()->with('error', 'Aucune validation de Thèse ne vous a été affectée.');
            }
        } catch (\Exception $e) {
            // Gérez les erreurs potentielles
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la soumission de votre rapport.');
        }
    }


    //index rapporteur
        public function indexRapporteur()
        {   $rapporteurs = User::where('type', 'rapporteur')->get();

            return view('rapporteurs.index',compact('rapporteurs'));
        }
    //Avis +  Rapport du rapporteur

    
    //============Response des rapporteurs================
    public function indexAvis()
    {
        // Fetch all thesis titles
        $theses = \App\Models\These::all();
        // Initialize an empty collection for the results
        $results = collect();
        return view('rapporteurs.avis', ['theses' => $theses, 'results' => $results]);
    }

    public function searchTheses(Request $request)
    {
        // Fetch all theses for the dropdown
        $theses = These::all();
        // Initialize an empty collection for the results
        $results = collect();
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $selectedThesis = These::where('titreOriginal', $searchTerm)->first();
            if ($selectedThesis) {
                $results = $selectedThesis->valider()
                    ->with(['rapporteur', 'these', 'membre'])
                    ->get()
                    ->map(function ($valider) {
                        return [
                            'titreOriginal' => $valider->these->titreOriginal,
                            'rapporteur' => $valider->rapporteur->name,
                            'avis' => $valider->avis,
                            'lien_rapport' => $valider->lien_rapport,
                        ];
                    });
            }
        }
        return view('rapporteurs.avis', ['theses' => $theses, 'results' => $results]);
    }

    public function submitReview(Request $request)
    {  
        // Valider la requête
        $request->validate([
            'director_review' => 'required|exists:theses,id',
            'avis' => 'required|in:oui,non',
        ]);

        // Récupérer les données de la requête
        $directorReview = $request->input('director_review');
        $avis = $request->input('avis');

        try {
            // Mettre à jour le champ acceptationDirecteur dans la table demandes
            These::where('id', $directorReview)
                ->update(['acceptationDirecteur' => $avis]);

            // Redirection avec un message de succès
            return redirect()->back()->with('success', 'Avis du directeur soumis avec succès.');
        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la soumission de l\'avis du directeur.' . $e->getMessage());
        }
    }

}

