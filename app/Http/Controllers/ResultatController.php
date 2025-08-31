<?php

namespace App\Http\Controllers;

use App\Models\MembreArabe;
use Illuminate\Http\Request;
use App\Models\Soutenance;
use App\Models\Resultat;
use App\Models\Evaluer;
use App\Models\Doctorant;
use App\Models\Effectuer;

use Carbon\Carbon;

class ResultatController extends Controller
{
   
    public function index()
    {
        $doctorants = Doctorant::whereDoesntHave('effectuer', function ($query) {
                $query->whereHas('resultat');
            })
            ->with(['soutenances' => function ($query) {
                $query->whereDate('date', '<', now())->orderBy('date', 'desc')->whereDoesntHave('resultat');
            }, 'these', 'effectuer'])
            ->get();

        return view('resultats', compact('doctorants'));
    }
    // public function index()
    // {
    //     $soutenances = Soutenance::whereDate('date', '<', now()) // Filter past soutenances
    //     ->orderBy('date', 'desc')
    //     ->whereDoesntHave('resultats') // Filter soutenances without results
    //     ->with(['encadrant', 'doctorant', 'effectuer']) // Eager load relationships
    //     ->get();
    //         dd($soutenances);
    //     return view('resultats', compact('soutenances'));
    // }



    public function show($id)
    {
        // Fetch the soutenance details using the $id
        $soutenance = Soutenance::findOrFail($id);
    
        // You may need to eager load related models here if necessary
        $soutenance->load('doctorant.these');
    
        // Pass the soutenance details to the view
        return view('resultat-formulaire', compact('soutenance'));
    }

    

    
    


    // public function showAutreInformation($id_soutenance)
    // {
    //     // Fetch members who had the specified id_soutenance
    //     $membres = Evaluer::where('id_soutenance', $id_soutenance) 
    //     ->with(['membre', 'membre.membreArabe'])
    //     ->get();

    //     // Pass the membres and id_soutenance to the view
    //     return view('autre-information', compact('membres', 'id_soutenance'));
    // }

    public function showAutreInformation($id_soutenance)
    {
        // Fetch the Soutenance with the specified id_soutenance
        $soutenance = Soutenance::findOrFail($id_soutenance);

        // Fetch the Effectuer record associated with the specified id_soutenance
        $effectuer = Effectuer::where('id_soutenance', $id_soutenance)->firstOrFail();

        // Get the id_doctorant associated with the Effectuer record
        $id_doctorant = $effectuer->id_doctorant;

        // Fetch members who had the specified id_soutenance
        $membres = Evaluer::where('id_soutenance', $id_soutenance) 
                            ->with(['membre', 'membre.membreArabe'])
                            ->get();

        // Pass the membres, id_soutenance, and id_doctorant to the view
        return view('autre-information', compact('membres', 'id_soutenance', 'id_doctorant'));
    }
    // if titreFinal ->nullable()
        
    //     public function store(Request $request, $id)
    // {
    //     // Validate the incoming request data
    //     $validatedData = $request->validate([
    //         'observation' => 'required|in:Valider,Rattraper',
    //         'mention' => 'required|in:Passable,Honorable,Très Honorable,Très Honorable avec félicitations du jury',
    //         'formationDoctorale' => 'required|string',
    //         'specialite' => 'required|string',
    //         'titreFinal' => 'required|string',
    //     ]);

    //     // Find the associated soutenance
    //     $soutenance = Soutenance::findOrFail($id);

    //     // Retrieve the doctorant associated with the soutenance
    //     $doctorant = $soutenance->doctorants->first();

    //     // Check if the doctorant exists
    //     if ($doctorant) {
    //         // Create a new these with the titreFinal
    //         $these = $doctorant->theses()->create([
    //             'titreFinal' => $validatedData['titreFinal'],
    //         ]);

    //         // If you have more data to insert into the these table, you can add them here
    //         // For example:
    //         // $these->fill([...])->save();
    //     } else {
    //         // Log an error or handle the case where the doctorant is not found
    //         logger()->error('Doctorant not found for soutenance ID: ' . $id);
    //         // You can return a response or perform any other action here
    //     }

    //     // Create a new Resultat instance with the validated data
    //     $resultat = new Resultat([
    //         'observation' => $validatedData['observation'],
    //         'mention' => $validatedData['mention'],
    //         'formationDoctorale' => $validatedData['formationDoctorale'],
    //         'specialite' => $validatedData['specialite'],
    //         'id_soutenance' => $soutenance->id,
    //     ]);

    //     // Save the Resultat to the database
    //     $resultat->save();

    //     // Redirect back with a success message
    //     return redirect()->back()->with('success', 'Résultat et thèse ajoutés avec succès.');
    // }

    public function store(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'observation' => 'required|in:Valider,Rattraper',
            'mention' => 'required|in:Passable,Honorable,Très Honorable,Très Honorable avec félicitations du jury',
            'formationDoctorale' => 'required|string',
            'specialite' => 'required|string',
            'titreFinal' => 'required|string',
        ]);

        // Find the associated soutenance
        $soutenance = Soutenance::findOrFail($id);

        // Retrieve the doctorant associated with the soutenance
        $doctorant = $soutenance->doctorants->first();

        // Check if the doctorant exists
        if ($doctorant) {
            // Update the titreFinal attribute for the theses associated with the doctorant
            $doctorant->theses->each(function ($these) use ($validatedData) {
                $these->update(['titreFinal' => $validatedData['titreFinal']]);
            });
        } else {
            // Log an error or handle the case where the doctorant is not found
            logger()->error('Doctorant not found for soutenance ID: ' . $id);
            // You can return a response or perform any other action here
        }

        // Create a new Resultat instance with the validated data
        $resultat = new Resultat([
            'observation' => $validatedData['observation'],
            'mention' => $validatedData['mention'],
            'formationDoctorale' => $validatedData['formationDoctorale'],
            'specialite' => $validatedData['specialite'],
            'id_soutenance' => $soutenance->id,
        ]);

        // Save the Resultat to the database
        $resultat->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Résultat et thèse ajoutés avec succès.');
    }


}
