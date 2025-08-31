<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\These;
use App\Models\Doctorant;
use App\Models\Membre;
use Carbon\Carbon;

class TheseController extends Controller
{

    public function index()
    {

       $theses = These::all();
       return view('theses.index', compact('theses'));

    }

    public function create()
    {
        $doctorantsCIN = Doctorant::pluck('CINE');

        // Passer les CIN des doctorants à la vue
        return view('theses.create', compact('doctorantsCIN'));    
    }



    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'titreOriginal' => 'required|string',
            'titreFinal' => 'nullable|string',
            'formation' => 'required|string',
            'cine' => 'required|string',
            'acceptationDirecteur' => 'nullable|in:Oui,Non',
            'structure_recherche' => 'required|string',
            'other_structure' => 'nullable|string',
            'date_premiere_inscription' => 'required|date',
            'nombre_publications_article' => 'required|integer|min:0',
            'nombre_publications_conference' => 'required|integer|min:0',
            'nombre_communications' => 'required|integer|min:0',
        ]);
    
        // Rechercher le doctorant correspondant au CIN saisi
        $doctorant = Doctorant::where('CINE', $request->cine)->first();
    
        // Vérifier si le doctorant existe
        if (empty($doctorant)) {
            // Gérer le cas où aucun doctorant correspondant au CIN n'est trouvé
            return back()->withErrors(['error' => 'Aucun doctorant trouvé avec ce CINE!']);
        }
    
        // Déterminer la structure de recherche
        $structureRecherche = $request->structure_recherche === 'Other' ? $request->other_structure : $request->structure_recherche;
    
        // Créer une nouvelle thèse
        $these = These::create([
            'titreOriginal' => $request->titreOriginal,
            'titreFinal' => $request->titreFinal,
            'formation' => $request->formation,
            'id_doctorant' => $doctorant->id, // Utiliser l'ID du doctorant trouvé
            'acceptationDirecteur' =>'Oui',
            'structure_recherche' => $structureRecherche,
            'date_premiere_inscription' => $request->date_premiere_inscription,
            'nombre_publications_article' => $request->nombre_publications_article,
            'nombre_publications_conference' => $request->nombre_publications_conference,
            'nombre_communications' => $request->nombre_communications,
        ]);
    
        // Stocker l'ID de la thèse dans la session
        session(['id_these' => $these->id]);
         
        // Rediriger l'utilisateur avec un message de succès
        return redirect()->route('demandes.create', compact('these'))->with('success', 'Thèse créée avec succès!');
    }
    
    


    public function show($id)
    {
        // Récupérez les détails de la thèse depuis la base de données
        $these = These::findOrFail($id);
        
        // Récupérez les détails du doctorant associé à cette thèse
        $doctorant = $these->doctorant;
        
        // Récupérez les détails de l'encadrant associé au doctorant
        $encadrant = $doctorant->encadrant;
        
        return view('theses.show', compact('these', 'doctorant', 'encadrant'));
    }
    
    





    public function edit($id)
    {
     
    
        // Récupérez la thèse à éditer
        $these = These::findOrFail($id);
       
        session(['id_these' => $these->id]);
        // Récupérez la liste des doctorants pour le formulaire de modification
        $doctorants = Doctorant::all();
        $date_premiere = Carbon::parse($these->date_premiere_inscription);
    
        return view('theses.edit', compact('these', 'doctorants','date_premiere'));
    }




    public function update(Request $request, $id)
    {
        // Valider les données du formulaire
        $request->validate([
            'titreOriginal' => 'required|string',
            'titreFinal' => 'nullable|string',
            'formation' => 'required|string',
            'cine' => 'required|string',
            'acceptationDirecteur' => 'nullable|in:Oui,Non',
            'structure_recherche' => 'required|string',
            'other_structure' => 'nullable|string',
            'date_premiere_inscription' => 'required|date',
            'nombre_publications_article' => 'required|integer|min:0',
            'nombre_publications_conference' => 'required|integer|min:0',
            'nombre_communications' => 'required|integer|min:0',
        ]);
    
        // Rechercher la thèse à mettre à jour
        $these = These::findOrFail($id);
    
        // Rechercher le doctorant correspondant au CIN saisi
        $doctorant = Doctorant::where('CINE', $request->cine)->first();
    
        // Vérifier si le doctorant existe
        if (empty($doctorant)) {
            // Gérer le cas où aucun doctorant correspondant au CIN n'est trouvé
            return back()->withErrors(['error' => 'Aucun doctorant trouvé avec ce CINE!']);
        }
    
        // Déterminer la structure de recherche
        $structureRecherche = $request->structure_recherche === 'autre' ? $request->other_structure : $request->structure_recherche;
    
        // Mettre à jour les détails de la thèse
        $these->update([
            'titreOriginal' => $request->titreOriginal,
            'titreFinal' => $request->titreFinal,
            'formation' => $request->formation,
            'id_doctorant' => $doctorant->id, // Utiliser l'ID du doctorant trouvé
            'acceptationDirecteur' => $request->acceptationDirecteur,
            'structure_recherche' => $structureRecherche,
            'date_premiere_inscription' => $request->date_premiere_inscription,
            'nombre_publications_article' => $request->nombre_publications_article,
            'nombre_publications_conference' => $request->nombre_publications_conference,
            'nombre_communications' => $request->nombre_communications,
        ]);
    
        // Rediriger l'utilisateur avec un message de succès
        return redirect()->route('theses.edit', $these->id)->with('success', 'Thèse mise à jour avec succès.');
    }
    
    public function destroy($id)
    {
        // Trouver et supprimer la thèse
        $these = These::findOrFail($id);
        $these->delete();

        // Rediriger l'utilisateur avec un message de succès
        return redirect()->route('theses.index')->with('success', 'Thèse supprimée avec succès.');
    }

//recuperer une these valider par le rapporteur

}