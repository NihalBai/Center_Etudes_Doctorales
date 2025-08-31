<?php

use App\Models\Doctorant;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MembreController;

use App\Http\Controllers\SoutenanceController;
use App\Http\Controllers\ResultatController;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\DocumentController;

use App\Http\Controllers\UniversiteController;
use App\Http\Controllers\FaculteController;
use App\Http\Controllers\MembreArabeController;
use App\Models\User;
use App\Http\Controllers\DoctorantController;
use App\Http\Controllers\GradeController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\TheseController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\DoctorantArabeController;





    Route::post('/create', [UserController::class, 'store'])->name('users.store');
    Route::get('/create', function () {
        return view('create');
    })->name('create');




    Route::get('/diplome', function () {
        return view('diplom');
    });
    

// Route::post('/create', [UserController::class, 'store'])->name('users.store');




Route::get('/', function () {
    return view('welcome');
});

Route::get('/resultat', function () {
    return view('resultat-formulaire');
});

Route::get('/profile', function () {
    return view('user.profile1');
});

Route::get('/autre-information', function () {
    return view('autre-information');
});

Route::get('/test', function () {
    return view('test');
});
// ->name('test')->middleware('auth');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('accueil');
    })->name('dashboard');
});

//Ajouter route 

// Route::get('/AjouterMembre', function () {
//     return view('ajouter-membre');
// });


// membres.index: Route for displaying the list of members.
// membres.search: Route for searching members.
// ajouter-membre: Route for showing the form to add a new member.
// membres.store: Route for storing a new member.


Route::delete('/utilisateurs/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::patch('/utilisateurs/{id}/etat', [UserController::class, 'updateEtat'])->name('users.updateEtat');
Route::get('/utilisateurs', [UserController::class, 'index1'])->name('users.index');


Route::get('/membres', [MembreController::class, 'index'])->name('membres.index');
// ->middleware('auth')
Route::get('/membres/search', [MembreController::class, 'search'])->name('membres.search');
Route::get('/ajouter-membre', [MembreController::class, 'create'])->name('ajouter-membre');
Route::post('/ajouter-membre', [MembreController::class, 'store'])->name('membres.store');
Route::get('/membres/{id}/profil', [MembreController::class, 'show'])->name('membres.profil');
Route::resource('membres', MembreController::class);
Route::put('/membres/{membre}', [MembreController::class, 'update'])->name('membres.update');

// Route::get('/ajouter-membre', [MembreController::class, 'getFacultesAndCities'])->name('get.facultes.cities');
// utiliser pour remplire tableau Grades pour la premiere fois 
// Route::get('/ajouter-membre', [MembreController::class, 'insertGrades']);


// users

// Route::get('/profile/{user}', [UserController::class, 'show'])->name('profile.show');
Route::get('/profile', [UserController::class, 'index'])->name('profile.index');
// Route::get('/profile', [UserController::class, 'index1'])->name('profile.index');
Route::post('/profile', [UserController::class, 'update'])->name('profile.update');

Route::put('/profile/{user}', [UserController::class, 'update'])->name('profile.update');
// Route::post('/profile', [UserController::class, 'changePassword'])->name('change.password');
// Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('password.update');


Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/login', [UserController::class, 'login'])->name('loginñ');


// Route::get('/login', function () {
//     // Define the logic for the "test" route
//     return view('auth.login'); // Assuming you have a view named "test"
// })->name('login');


// Route for showing the password reset form
Route::get('/reset-password', [UserController::class, 'showResetForm'])->name('password.reset');

// Route for updating the password
Route::post('/update-password', [UserController::class, 'updatePassword'])->name('password.update');

// Route::get('/profile/{user}', 'UserController@show')->name('profile.show');


// Route for creating "avis de soutenance" list of doctorats ;
Route::get('/avisdesoutenance',[SoutenanceController::class, 'index'])->name('soutenance.index');
Route::get('/avisdesoutenance/create/{doctorantId}',[SoutenanceController::class, 'create'])->name('soutenance.create');
Route::post('/avisdesoutenance/create',[SoutenanceController::class, 'store'])->name('soutenance.store');



// Routes for creating listing and modifying the soutenance
Route::get('/avisdesoutenance/lsite',[SoutenanceController::class, 'getDoctorantsWithSoutenance'])->name('soutenance.liste');
Route::get('/avisdesoutenance/show/{doctorantId}',[SoutenanceController::class, 'show'])->name('soutenance.show');


Route::get('/avisdesoutenance/edit/{doctorantId}',[SoutenanceController::class, 'edit'])->name('soutenance.edit');
Route::post('/avisdesoutenance/edit',[SoutenanceController::class,'Update'])->name('soutenance.update');
Route::post('/avisdesoutenance/edit/{doctorantId}',[SoutenanceController::class, 'edit'])->name('soutenance.edit');




// Route for results
Route::get('/Resultat', [ResultatController::class, 'index'])->name('resultats.index');


// Document generation Routes 
// Route::post('/generate-certificate/{doctorantId}', [DocumentController::class, 'generateAvisDeSoutenance'])->name('soutenance.avis');
// Route::post('/generate-attestation_jury/{doctorantId}', [DocumentController::class, 'attestationDuJuryDeSoutenance'])->name('soutenance.attestation.jury');
// Route::post('/generate-invitation_jury/{doctorantId}', [DocumentController::class, 'invitationsDuJuryDeSoutenance'])->name('soutenance.invitation.jury');

// Route::get('/generate-diploma/{doctorantId}', [DocumentController::class, 'generateDiploma']);




// Route for creating "avis de soutenance" list of doctorats ;
Route::get('/creesoutenance',[SoutenanceController::class, 'index'])->name('soutenance.index');
Route::get('/creesoutenance/create/{doctorantId}',[SoutenanceController::class, 'create'])->name('soutenance.create');
Route::post('/creesoutenance/create',[SoutenanceController::class, 'store'])->name('soutenance.store');



// Routes for creating listing and modifying the soutenance
Route::get('/avisdesoutenance/lsite',[SoutenanceController::class, 'getDoctorantsWithSoutenance'])->name('soutenance.liste');
Route::get('/avisdesoutenance/show/{doctorantId}',[SoutenanceController::class, 'show'])->name('soutenance.show');


Route::get('/avisdesoutenance/edit/{doctorantId}',[SoutenanceController::class, 'edit'])->name('soutenance.edit');
Route::post('/avisdesoutenance/edit',[SoutenanceController::class,'Update'])->name('soutenance.update');
Route::post('/avisdesoutenance/edit/{doctorantId}',[SoutenanceController::class, 'edit'])->name('soutenance.edit');









// Route for results
Route::get('/Resultat', [ResultatController::class, 'index'])->name('resultats.index');


// Document generation Routes 
Route::post('/generate-certificate/{doctorantId}', [DocumentController::class, 'generateAvisDeSoutenance'])->name('soutenance.avis');
Route::post('/generate-attestation_jury/{doctorantId}', [DocumentController::class, 'attestationDuJuryDeSoutenance'])->name('soutenance.attestation.jury');
Route::post('/generate-attestation_doctorant/{doctorantId}', [DocumentController::class, 'generateAttestation'])->name('soutenance.attestation.doctorant');
Route::post('/generate-rapport-soutenance/{doctorantId}', [DocumentController::class, 'generateRapportDuJuryDeSoutenance'])->name('soutenance.rapport');
Route::post('/generate-diploma/{doctorantId}', [DocumentController::class, 'generateDiploma'])->name('soutenance.diploma');
Route::post('/generate-invitation_jury/{doctorantId}', [DocumentController::class, 'downloadJuryInvitations'])->name('soutenance.invitation.jury');

Route::get('/generate-diploma/{doctorantId}', [DocumentController::class, 'generateDiploma']);



//resultat

Route::get('/Resultat', [ResultatController::class, 'index'])->name('resultats.index')/* ->middleware('auth') */;
Route::get('/resultats/{id}/add', [ResultatController::class, 'show'])->name('resultats.show');
Route::post('/resultats/{id}/add', [ResultatController::class, 'store'])->name('resultats.store');
Route::get('/autre-information/{id_soutenance}', [ResultatController::class, 'showAutreInformation'])->name('autre-information');
Route::post('/autre-information', [MembreArabeController::class, 'store'])->name('membre-arabe.store');


Route::get('/diplome', [DoctorantController::class, 'showDoctorantsWithResults'])->name('doctorants-with-results');
Route::get('/Diplome', [DoctorantController::class, 'showDoctorantsWithResults'])->name('diplom');
Route::get('/doctorant/{id}', [DoctorantController::class, 'redirectToAppropriatePage'])->name('doctorant-redirect');
// Route::get('/autre-information/{id}', [DoctorantController::class, 'index'])->name('autre-information');

Route::get('/final-page/{id}', function ($id) {
    // Logic for handling the final page
    return view('final-page', compact('id'));
})->name('final-page');
// Route::get('/ajouter-membre', [UniversiteController::class, 'getByCity']);
// Route::get('/ajouter-membre', [FaculteController::class, 'getByUniversity']);
// Route::get('/ajouter-membre', [FaculteController::class, 'cities']);






Route::get('/templates', [DocumentController::class, 'index'])->name('templates');
Route::get('/templates/{template}/print', [DocumentController::class, 'printTemplate'])->name('print.template');
Route::post('/templates/{template}/replace', [DocumentController::class, 'replaceTemplate'])->name('replace.template');



Route::get('/gestion', [GradeController::class, 'index'])->name('gestion');
Route::post('/store/{model}', [GradeController::class, 'store'])->name('store');
Route::get('/edit/{model}/{id}', [GradeController::class, 'edit'])->name('edit');
Route::put('/update/{model}/{id}', [GradeController::class, 'update'])->name('update');
Route::get('/search', [GradeController::class, 'search'])->name('search');

Route::get('/profile', [UserController::class, 'index'])->name('profile.index');
// Route::get('/profile', [UserController::class, 'index1'])->name('profile.index');
Route::post('/profile', [UserController::class, 'update'])->name('profile.update');

Route::post('/profile/{user}', [UserController::class, 'update'])->name('profile.update');

Route::get('/utilisateurs/{user}/compte', [UserController::class, 'manageAccount'])->name('user.account');
Route::put('/utilisateurs/{userId}', [UserController::class, 'updateuser'])->name('user.update');


// DEMANDES DE SOUTENANCE
Route::get('/demandes/{demande}/edit', [DemandeController::class, 'edit'])->name('demandes.edit');
Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
Route::get('/demandes/create', [DemandeController::class, 'create'])->name('demandes.create');
Route::post('/demandes', [DemandeController::class, 'store'])->name('demandes.store');

Route::put('/demandes/{demande}', [DemandeController::class, 'update'])->name('demandes.update');
Route::delete('/demandes/{demande}', [DemandeController::class, 'destroy'])->name('demandes.destroy');
//Generer le pv individuel=   la demande de soutenance 
Route::get('/demandes/{id}/download', [DemandeController::class, 'downloadDemande'])->name('demandes.download');

//GESTION DE DOCTORANTS
Route::get('/doctorants/create/arabe', [DoctorantArabeController::class, 'create'])->name('doctorants.createArabe');
Route::post('/doctorants/store/arabe', [DoctorantArabeController::class, 'store'])->name('doctorants.storeArabe');

Route::put('/doctorants/update-scolarite', [DoctorantController::class, 'updateScolarite'])->name('doctorants.updateScolarite');
Route::post('/doctorants/store', [DoctorantController::class, 'store'])->name('doctorants.store');
Route::get('/doctorants', [DoctorantController::class, 'index'])->name('doctorants.index');
Route::get('/doctorants/create', [DoctorantController::class, 'create'])->name('doctorants.create');
Route::put('/doctorants/{doctorant}', [DoctorantController::class, 'update'])->name('doctorants.update');
Route::delete('/doctorants/{doctorant}', [DoctorantController::class, 'destroy'])->name('doctorants.destroy');
Route::get('/doctorants/profil/{id}', [DoctorantController::class, 'profil'])->name('doctorants.profil');
Route::post('/doctorants/storeFiles', [DoctorantController::class, 'storeFile'])->name('doctorants.storeFile');
Route::get('/doctorants/createFiles', [DoctorantController::class, 'createFile'])->name('files.create');
//==================================test==================================================
Route::get('/generate-pdf/{id}', [DoctorantController::class, 'generateIndividualPV'])->name('pvs.individual');
//====================================test=====================================================================

//GESTION DE THESES

Route::get('/theses/create', [TheseController::class, 'create'])->name('theses.create');
Route::get('/theses/{id}', [TheseController::class, 'show'])->name('theses.show');
Route::get('/theses', [TheseController::class, 'index'])->name('theses.index');
Route::get('/theses/{these}/edit', [TheseController::class, 'edit'])->name('theses.edit');
Route::put('/theses/{these}', [TheseController::class, 'update'])->name('theses.update');
Route::post('/theses/store', [TheseController::class, 'store'])->name('theses.store');

//GESTION DES COMMISSIONS DES THESES
// In routes/web.php
Route::post('commissions/{session_id}/doctorants/{doctorant_id}/updatepvindividuel', [CommissionController::class, 'updatepvindividuel'])->name('commissions.updatepvindividuel');
Route::get('commissions/create', [CommissionController::class, 'create'])->name('commissions.create');
Route::get('commissions', [CommissionController::class, 'commissions'])->name('commissions.commissions');
Route::get('/commissions/pv', [CommissionController::class, 'index'])->name('commissions.index');
Route::post('/commissions/download-selected', [CommissionController::class, 'downloadSelected'])->name('commissions.downloadSelected'); // Ajout de la route pour downloadSelected
Route::get('/commissions/download-all', [CommissionController::class, 'downloadAll'])->name('commissions.downloadAll'); // Ajout de la route pour downloadAll
Route::post('/commissions/search', [CommissionController::class, 'search'])->name('commissions.search'); // Ajout de la route pour downloadAll
Route::post('/commissions/filter', [CommissionController::class, 'filterDemandes'])->name('commissions.filter');
Route::get('/commissions/downloadFile/{path}', [CommissionController::class, 'downloadFile'])->name('commissions.downloadFile');
Route::post('/commissions/download-files', [CommissionController::class, 'downloadFiles'])->name('commissions.downloadFiles');

Route::post('/commissions/search-by-session', [CommissionController::class, 'searchBySession'])->name('commissions.searchBySession');
Route::post('/commissions/search-by-etat', [CommissionController::class, 'searchByEtat'])->name('commissions.searchByEtat');
Route::post('/generate-pv-global', [CommissionController::class, 'generatePVGlobal'])->name('commissions.generate_PVGloabal');
Route::get('commissions/{idSession}', [CommissionController::class, 'showIndividualPVs'])->name('commissions.individualPVs');//generer le pv apres le passege de la commission

Route::get('/commissions/edit/{sessionId}', [CommissionController::class, 'edit'])->name('commissions.edit');
Route::put('/commissions/{commission}', [CommissionController::class, 'update'])->name('commissions.update');
Route::post('commissions/store', [CommissionController::class, 'store'])->name('commissions.store');

//GESTION DES RAPPORTEURS
Route::get('/rapporteurs', [UserController::class, 'indexRapporteur'])->name('rapporteurs.index');
Route::get('/rapporteurs/create',  [UserController::class, 'createRapporteur'])->name('rapporteurs.create');
Route::post('/rapporteurs/store', [UserController::class, 'storeRapporteur'])->name('rapporteurs.storeRapporteur');
Route::get('/rapporteurs/document', [UserController::class, 'ajouterDocuments'])->name('rapporteurs.ajouterDocuments');
Route::get('/rapporteurs/avis', [UserController::class, 'indexAvis'])->name('rapporteurs.avis');
Route::get('/avis/search', [UserController::class, 'searchTheses'])->name('rapporteurs.avis.search');
Route::post('/director/review', [UserController::class, 'submitReview'])->name('director.review.submit');
//recuperer une these qui sera valider par le rapporteur dans son compte
Route::get('/profilRapporteur', [UserController::class, 'profilRapporteur'])->name('rapporteurs.profil');
Route::post('/submit-rapport', [UserController::class, 'submitRapport'])->name('submitRapport');
Route::get('/rapporteurs/rapport',  [UserController::class, 'affiche'])->name('rapporteurs.file');
Route::post('/rapporteurs', [UserController::class, 'upload'])->name('rapporteurs.upload');
Route::get('/rapporteur/{id}', [UserController::class, 'compte'])->name('rapporteur.compte');
Route::post('/rapporteurs/documents', [UserController::class, 'joindreDocuments'])->name('rapporteurs.joindreDocuments');
//===================