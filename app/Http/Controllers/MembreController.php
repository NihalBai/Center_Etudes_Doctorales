<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membre;
use App\Models\Autre;
use App\Models\Faculte;
use App\Models\Universite;
use App\Models\Grade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MembreController extends Controller
{
    // public function index()
    // {
    //     $membres = Membre::all(); // Fetching all membres
    //     return view('membres', ['membres' => $membres]); // Pass the $membres variable to the view
    // }
    public function index()
    {
        // Eager load the grade and faculte relationships
        $membres = Membre::with(['grades', 'faculte'])->get();
        return view('membres', ['membres' => $membres]); // Pass the $membres variable to the view
    }
    

   

    public function testDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return "Connected successfully to the database.";
        } catch (\Exception $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string', // Allow the search parameter to be nullable and a string
        ]);

        $search = $request->input('search');

        // Define an array of searchable fields
        $searchableFields = ['nom', 'prenom', 'email', 'sex', 'tele', 'grade'];

        $query = Membre::with(['faculte', 'universite', 'autre']);

        if ($search) {
            $query->where(function ($query) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $query->orWhere($field, 'LIKE', "%$search%");
                }
                // Search within the affiliation's name
                $query->orWhereHas('faculte', function ($query) use ($search) {
                    $query->where('nom', 'LIKE', "%$search%");
                })->orWhereHas('universite', function ($query) use ($search) {
                    $query->where('nom', 'LIKE', "%$search%");
                })->orWhereHas('autre', function ($query) use ($search) {
                    $query->where('nom', 'LIKE', "%$search%");
                });
            });
        }

        // Get the search results
        $membres = $query->get();

        return view('membres', ['membres' => $membres]);
    }




    public function create()
    {
        $grades = Grade::distinct()->pluck('nom');

        // Fetch data from other tables as needed
        $facultes = Faculte::all();
        $universites = Universite::all();
        $villes = Faculte::distinct()->pluck('ville');
        $autres = Autre::pluck('nom', 'ville', 'id')->toArray();

        return view('ajouter-membre', compact('facultes', 'universites', 'villes', 'autres', 'grades'));
    }


// public function store(Request $request)
// {
//     // Display all incoming request data for debugging purposes
//     // dd($request->all());

//     // Validate the incoming request data
//     $validatedData = $request->validate([
//         'nom' => 'required|string',
//         'prenom' => 'required|string',
//         'email' => 'required|email|unique:membres',
//         'sex' => 'required|in:male,female',
//         'tele' => 'required|string',
//         'grade' => 'required|string',
//         'id_faculte' => 'nullable|exists:facultes,id',
//         'id_universite' => 'nullable|exists:universites,id',
//         'id_autre' => 'nullable|exists:autres,id',
//         'newgrade' => 'nullable|required_if:grade,autre|string',
//     ]);

//     // Create a new Membre instance with the validated data
//     $membre = new Membre([
//         'nom' => $validatedData['nom'],
//         'prenom' => $validatedData['prenom'],
//         'email' => $validatedData['email'],
//         'sex' => $validatedData['sex'],
//         'tele' => $validatedData['tele'],
//     ]);

//     // Assign faculte and universite based on the selected universite
//     if ($request->filled('universite')) {
//         if ($request->input('universite') === 'autre') {
//             // Create a new universite
//             $universite = new Universite([
//                 'nom' => $request->input('newuniversite'),
//             ]);
//             $universite->save();

//             // Create a new faculte associated with the new universite
//             $faculte = new Faculte([
//                 'nom' => $request->input('newfaculte'),
//                 'ville' => $request->input('ville') === 'autre' ? $request->input('newville') : $request->input('ville'),
//                 'id_universite' => $universite->id,
//             ]);
//             $faculte->save();
//         } else {
//             // Find the selected universite
//             $universite = Universite::find($request->input('universite'));

//             // Check if faculte is "autre"
//             if ($request->input('faculte') === 'autre') {
//                 // Create a new faculte
//                 $faculte = new Faculte([
//                     'nom' => $request->input('newfaculte'),
//                     'ville' => $request->input('ville') === 'autre' ? $request->input('newville') : $request->input('ville'),
//                     'id_universite' => $universite->id,
//                 ]);
//                 $faculte->save();
//             } else {
//                 // Find the selected faculte
//                 $faculte = Faculte::where('id_universite', $universite->id)
//                                     ->where('nom', $request->input('faculte'))
//                                     ->first();
//                 // If the faculte does not exist, create a new one
//                 if (!$faculte) {
//                     $faculte = new Faculte([
//                         'nom' => $request->input('faculte'),
//                         'ville' => $request->input('ville') === 'autre' ? $request->input('newville') : $request->input('ville'),
//                         'id_universite' => $universite->id,
//                     ]);
//                     $faculte->save();
//                 }
//             }
//         }
//         // Associate the membre with the faculte
//         $membre->id_faculte = $faculte->id;
//     }

//     // Handle "autre" case
//     if ($request->filled('autre')) {
//         $autre = new Autre([
//             'nom' => $request->input('autre'),
//             'ville' => $request->input('ville') === 'autre' ? $request->input('newville') : $request->input('ville'),
//         ]);
//         $autre->save();

//         // Associate the membre with the autre
//         $membre->id_autre = $autre->id;
//     }

//     // Save the Membre to the database
//     $membre->save();

//     // Create or find the associated grade
//     if ($request->input('grade') === 'autre') {
//         // If the selected grade is "autre", use the value from the "newgrade" input
//         $grade = Grade::firstOrCreate(['nom' => $validatedData['newgrade']]);
//     } else {
//         // If it's not "autre", create or find the grade based on the selected grade
//         $grade = Grade::firstOrCreate(['nom' => $validatedData['grade']]);
//     }

//     // Assign the member ID to the grade
//     $grade->id_membre = $membre->id;
//     $grade->save();

//     // Redirect back with a success message
//     return redirect()->back()->with('success', 'Membre ajouté avec succès.');
// }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:membres',
            'sex' => 'required|in:male,female',
            'tele' => 'required|string',
            'grade' => 'required|string',
            'id_faculte' => 'nullable|exists:facultes,id',
            'id_universite' => 'nullable|exists:universites,id',
            'id_autre' => 'nullable|exists:autres,id',
            'newgrade' => 'nullable|required_if:grade,autre|string',
        ]);

        // Create a new Membre instance with the validated data
        $membre = new Membre([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'email' => $validatedData['email'],
            'sex' => $validatedData['sex'],
            'tele' => $validatedData['tele'],
        ]);

        // Assign faculte and universite based on the selected universite
        if ($request->filled('universite')) {
            if ($request->input('universite') === 'autre') {
                // Create a new universite
                $universite = Universite::firstOrCreate([
                    'nom' => $request->input('newuniversite'),
                ]);

                // Create a new faculte associated with the new universite
                $faculte = Faculte::firstOrCreate([
                    'nom' => $request->input('newfaculte'),
                    'ville' => $request->input('ville') === 'autre' ? $request->input('newville') : $request->input('ville'),
                    'id_universite' => $universite->id,
                ]);
            } else {
                // Find the selected universite
                $universite = Universite::find($request->input('universite'));

                // Check if faculte is "autre"
                if ($request->input('faculte') === 'autre') {
                    // Create a new faculte
                    $faculte = Faculte::firstOrCreate([
                        'nom' => $request->input('newfaculte'),
                        'ville' => $request->input('ville') === 'autre' ? $request->input('newville') : $request->input('ville'),
                        'id_universite' => $universite->id,
                    ]);
                } else {
                    // Find or create the selected faculte
                    $faculte = Faculte::firstOrCreate([
                        'id' => $request->input('faculte'), // Use the `id` from the request
                    ], [
                        'id_universite' => $universite->id,
                        'nom' => $request->input('faculte'),
                        'ville' => $request->input('ville') === 'autre' ? $request->input('newville') : $request->input('ville')
                    ]);
                }
            }
            // Associate the membre with the faculte
            $membre->id_faculte = $faculte->id;
        }

        // Handle "autre" case
        if ($request->filled('autre')) {
            $autre = Autre::firstOrCreate([
                'nom' => $request->input('autre'),
                'ville' => $request->input('ville') === 'autre' ? $request->input('newville') : $request->input('ville'),
            ]);

            // Associate the membre with the autre
            $membre->id_autre = $autre->id;
        }

        // Save the Membre to the database
        $membre->save();

        // Create or find the associated grade
        if ($request->input('grade') === 'autre') {
            // If the selected grade is "autre", use the value from the "newgrade" input
            $grade = Grade::firstOrCreate(['nom' => $validatedData['newgrade']]);
        } else {
            // If it's not "autre", create or find the grade based on the selected grade
            $grade = Grade::firstOrCreate(['nom' => $validatedData['grade']]);
        }

        // Attach the grade to the member using the pivot table
        $membre->grades()->attach($grade);


        // Redirect back with a success message
        return redirect()->back()->with('success', 'Membre ajouté avec succès.');
    }



    public function show($id)
    {
        $membre = Membre::findOrFail($id);

        $soutenances = $membre->soutenancesEvaluees()
                            ->with(['doctorants', 'encadrant', 'localisation', 'theses'])
                            ->get();

        $theses = $membre->thesesValidees()
                        ->with(['doctorant', 'encadrant'])
                        ->get();

        // Debugging: Log the data to ensure it's being retrieved
        logger()->info('Soutenances:', $soutenances->toArray());
        
        logger()->info('Theses:', $theses->toArray());

        $facultes = Faculte::all();
        $autres = Autre::all();
        $universites = Universite::all();
        $villes = Faculte::distinct()->pluck('ville');

        return view('membre-profile', compact('membre', 'soutenances', 'theses', 'facultes', 'autres', 'universites', 'villes'));
    }


// public function update(Request $request, Membre $membre)
// {
//     // Update membre information
//     $membre->update($request->only(['nom', 'prenom', 'email', 'sex', 'tele', 'id_faculte', 'id_autre']));

//     // Update grades
//     $membre->grades()->detach(); // Remove all existing grades first
//     if ($request->has('grades')) {
//         $gradeIds = [];
//         foreach ($request->input('grades') as $gradeName) {
//             $grade = Grade::firstOrCreate(['nom' => $gradeName]);
//             $gradeIds[] = $grade->id;
//         }
//         $membre->grades()->attach($gradeIds);
//     }

//     // Add new grade if provided
//     if ($request->has('new-grade')) {
//         $newGrade = Grade::firstOrCreate(['nom' => $request->input('new-grade')]);
//         $membre->grades()->attach($newGrade->id);
//     }

//     return redirect()->back()->with('success', 'Member information updated successfully.');
// }

public function update(Request $request, Membre $membre)
{
    // Validate the request inputs
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:membres,email,' . $membre->id,
        'sex' => 'required|in:male,female',
        'tele' => 'required|string|max:15',
        'id_faculte' => 'nullable|string|max:255',
        'ville' => 'nullable|string|max:255',
        'universite' => 'nullable|string|max:255',
        'id_autre' => 'nullable|exists:autres,id',
        'grades.*' => 'nullable|string|max:255',
        'new-grades.*' => 'nullable|string|max:255',
    ]);

    // Update membre information
    $membre->update($request->only(['nom', 'prenom', 'email', 'sex', 'tele', 'id_autre']));

    // Update existing grades
    if ($request->has('grades')) {
        foreach ($request->input('grades') as $gradeId => $gradeName) {
            $grade = Grade::find($gradeId);
            if ($grade) {
                $grade->update(['nom' => $gradeName]);
            }
        }
    }

    // Add new grades if provided
if ($request->has('new-grades')) {
    foreach ($request->input('new-grades') as $newGradeName) {
        if (!empty($newGradeName)) {
            $newGrade = Grade::firstOrCreate(['nom' => $newGradeName]);
            $membre->grades()->attach($newGrade->id);
        }
    }
}

    // Update faculte, ville, and universite
    $faculte = Faculte::where('nom', $request->input('faculte'))
                    ->where('ville', $request->input('ville'))
                    ->whereHas('universite', function ($query) use ($request) {
                        $query->where('nom', $request->input('universite'));
                    })
                    ->first();

    if (!$faculte) {
        $universite = Universite::firstOrCreate(['nom' => $request->input('universite')]);

        $faculte = new Faculte();
        $faculte->nom = $request->input('faculte');
        $faculte->ville = $request->input('ville');
        $faculte->id_universite = $universite->id;
        $faculte->save();
    }

    // Assign faculte id to membre
    $membre->id_faculte = $faculte->id;
    $membre->save();

    return redirect()->back()->with('success', 'Member information updated successfully.');
}





}
