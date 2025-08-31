<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autre;
use App\Models\Faculte;
use App\Models\Universite;
use App\Models\Grade;

class GradeController extends Controller
{
    public function index()
    {
        $facultes = Faculte::with('universite')->get();
        $universites = Universite::all();
        $grades = Grade::all();

        return view('Gestion', compact('facultes', 'universites', 'grades'));
    }
   

    public function edit($model, $id)
    {
        $record = [];
    

        switch ($model) {
            case 'faculte':
                $faculte = Faculte::with('universite')->findOrFail($id);
                $record = [
                    'nom' => $faculte->nom,
                    'ville' => $faculte->ville,
                    'universite' => $faculte->universite->nom,
                ];
                break;
            case 'universite':
                $universite = Universite::findOrFail($id);
                $record = [
                    'nom' => $universite->nom,
                ];
                break;
            case 'grade':
                $grade = Grade::findOrFail($id);
                $record = [
                    'nom' => $grade->nom,
                ];
                break;
        }

        return response()->json($record);
    }
    public function store(Request $request, $model)
    {
        switch ($model) {
            case 'faculte':
                try {
                    // Retrieve the Universite with the specified ID
                    $universite = Universite::findOrFail($request->input('id_universite'));

                    // Check if the Faculte already exists
                    $existingFaculte = Faculte::where('nom', $request->input('nom'))
                        ->where('ville', $request->input('ville'))
                        ->where('id_universite', $universite->id)
                        ->first();

                    if ($existingFaculte) {
                        return redirect()->back()->with('warning', 'Cette faculté existe déjà.');
                    }

                    // Create a new Faculte instance
                    $faculte = new Faculte();
                    $faculte->nom = $request->input('nom');
                    $faculte->ville = $request->input('ville');

                    // Associate the Universite with the Faculte
                    $faculte->universite()->associate($universite);

                    // Save the Faculte instance
                    $faculte->save();

                    return redirect()->back()->with('success', 'Enregistrement enregistré avec succès.');
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    // Handle the case where the Universite is not found
                    return redirect()->back()->with('error', 'Université introuvable.');
                }

            case 'universite':
                // Check if the Universite already exists
                $existingUniversite = Universite::where('nom', $request->input('nom'))->first();

                if ($existingUniversite) {
                    return redirect()->back()->with('warning', 'Cette université existe déjà.');
                }

                $universite = new Universite();
                $universite->nom = $request->input('nom');
                $universite->save();
                break;

            case 'grade':
                // Check if the Grade already exists
                $existingGrade = Grade::where('nom', $request->input('nom'))->first();

                if ($existingGrade) {
                    return redirect()->back()->with('warning', 'Ce grade existe déjà.');
                }

                $grade = new Grade();
                $grade->nom = $request->input('nom');
                $grade->save();
                break;
        }

        return redirect()->back()->with('success', 'Enregistrement enregistré avec succès.');
    }
        
        public function update(Request $request, $model, $id)
        {
            switch ($model) {
                case 'faculte':
                    $faculte = Faculte::findOrFail($id);
                    $faculte->nom = $request->input('nom');
                    $faculte->ville = $request->input('ville');
                    $universite = Universite::where('nom', $request->input('universite'))->first();
        
                    // If the university record doesn't exist, create a new one
                    if (!$universite) {
                        $universite = new Universite();
                        $universite->nom = $request->input('universite');
                        $universite->save();
                    }
                    
                    // Associate the faculte with the universite (either existing or newly created)
                    $faculte->id_universite = $universite->id;
                    
                    $faculte->save();
                    break;
                case 'universite':
                    $universite = Universite::findOrFail($id);
                    $universite->nom = $request->input('nom');
                    $universite->save();
                    break;
                case 'grade':
                    $grade = Grade::findOrFail($id);
                    $grade->nom = $request->input('nom');
                    $grade->save();
                    break;
            }
        
            return redirect()->back()->with('success', 'Enregistrement mis à jour avec succès.');
        }
}
