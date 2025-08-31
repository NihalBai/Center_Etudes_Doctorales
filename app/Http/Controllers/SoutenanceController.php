<?php
namespace App\Http\Controllers;
use App\Models\Doctorant;
use Illuminate\Http\Request;
use App\Models\Membre;
use App\Models\Soutenance;
use App\Models\Evaluer;
use App\Models\Effectuer;
use App\Models\Localisation;
use Illuminate\Support\Facades\Log;


class SoutenanceController extends Controller
{
    public function index()
    {
        // Retrieve doctorants meeting specific criteria
        $doctorants = Doctorant::whereHas('these', function ($query) {
                $query->where('acceptationDirecteur', 'Oui');
            })
            ->whereDoesntHave('effectuer') // Check for absence of associated soutenances via effectuer pivot
            ->with('encadrant_id', 'theses') // Eager load relationships
            ->get();

        // Debugging: Inspect retrieved data
        // dd($doctorants);

        // Check if doctorants collection is not empty
        // if ($doctorants->isNotEmpty()) {
            // Return view with doctorants data
            return view('creeavisdesoutenance', compact('doctorants'));
        // } else {
        //     // Handle case where no doctorants match the criteria
        //     return view('creeavisdesoutenance')->with('message', 'No doctorants found matching the criteria.');
        // }
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string', // Allow the search parameter to be nullable and a string
        ]);

        $search = $request->input('search');

        // Define an array of searchable fields
        $searchableFields = ['nom', 'prenom', 'CINE']; // Add more fields as needed

        $query = Doctorant::with(['encadrant_id', 'these']);

        if ($search) {
            $query->where(function ($query) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $query->orWhere($field, 'LIKE', "%$search%");
                }
            });
        }

        // Filter by acceptationDirecteur = 'Oui'
        $query->whereHas('these', function ($query) {
            $query->where('acceptationDirecteur', 'Oui');
        });

        // Filter to exclude doctorants with scheduled soutenances
        $query->whereDoesntHave('soutenances');

        // Get the search results
        $doctorants = $query->get();

        return view('creeavisdesoutenance', ['doctorants' => $doctorants]);
    }


   



    public function create($doctorantId)
    {
        // Retrieve doctorant details
        $doctorant = Doctorant::findOrFail($doctorantId);

        
        // Retrieve localisations from the database
        $localisations = Localisation::all(); // Fetch all localisations from the 'localisations' table

        // Retrieve list of members
        $members = Membre::select('id', 'nom', 'prenom', 'sex')->get();

        // Get encadrant (advisor) as the default director of these
        $directorOfThese = $doctorant->encadrant_id;

        // Get the id of the corresponding these for the doctorant
        $theseId = $doctorant->theses()->where('acceptationDirecteur', 'Oui')->value('id');

        // Fetch all members (rapporteurs) who have validated the specified these
        $rapporteurs = Membre::whereHas('validations', function ($query) use ($theseId) {
            $query->where('id_these', $theseId);
        })->get();

        // Define the roles array for jury members
        $roles = [
            'Président',
            'Président/rapporteur',
            'Directeur de thèse',
            'Examinateur',
            'Rapporteur',
            'Co_directeur',
            "Invité",
        ];


        $lastNumeroOrdre = $this->getLastNumeroOrdre();


        // Pass data to the view
        return view('createavisdesoutenance', [
            'doctorant' => $doctorant,
            'localisations' => $localisations,
            'directorOfThese' => $directorOfThese,
            'rapporteurs' => $rapporteurs,
            'members' => $members,
            'lastNumeroOrdre' => $lastNumeroOrdre,
            'roles' => $roles, // Pass the roles array to the view
        ]);
    }
    
    public function getLastNumeroOrdre()
    {
        $lastNumeroOrdre = Effectuer::max('numero_ordre');
        return $lastNumeroOrdre ? $lastNumeroOrdre : 1;
    }


    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'doctorantId' => 'required|exists:doctorants,id',
            'date_time' => 'required|date_format:Y-m-d\TH:i',
            'localisation' => 'required|exists:localisations,id',
            'juryMembers' => 'required|array|min:5', // Ensure at least one jury member is selected
            'juryMembers.*.memberId' => 'required|exists:membres,id', // Validate each jury member ID
            'juryMembers.*.memberRole' => 'required|string', // Validate each jury member role
        ]);

        // Extract form data from JSON
        $data = json_decode($request->getContent(), true);

        // Output extracted data for debugging
        Log::info('Received doctorantId: ' . $data['doctorantId']);
        Log::info('Received dateTime: ' . $data['date_time']);
        Log::info('Received localisationId: ' . $data['localisation']);
        Log::info('Received juryMembers: ' . json_encode($data['juryMembers']));
        
        // Extracted data
        $doctorantId = $data['doctorantId'];
        $dateTime = $data['date_time'];
        $localisationId = $data['localisation'];
        $numeroDordre = $data['numeroDordre'];
        $juryMembers = $data['juryMembers'];

        // Check if the selected localisation is available for the whole day on the specified date
        $existingSoutenance = Soutenance::where('id_localisation', $localisationId)
        ->whereDate('date', date('Y-m-d', strtotime($dateTime)))
        ->first();

        if ($existingSoutenance) {
            return response()->json(['error' => 'Location already booked for the specified date', 'code' => 1], 422);
        }
        

        $existingNumeroDorder = Effectuer::where('numero_ordre', $numeroDordre)->exists();

        if ($existingNumeroDorder) {
            return response()->json(['error' => 'Duplicate order number', 'code' => 2], 422);
        }
        // Check if any jury member is part of another soutenance on the same day
        foreach ($juryMembers as $juryMember) {
            $memberId = $juryMember['memberId'];
            
            // Query to check if the member is part of another soutenance on the same date
            $existingJurySoutenance = Evaluer::whereHas('soutenance', function ($query) use ($dateTime) {
                $query->whereDate('date', date('Y-m-d', strtotime($dateTime)));
            })
            ->where('id_membre', $memberId)
            ->with('membre') // Eager load the member details
            ->exists();

            if ($existingJurySoutenance) {
                // Fetch the member details
                $member = Membre::findOrFail($memberId);

                // Construct the error message with member's name and family name
                $errorMessage = 'Jury member ' . $member->prenom . ' ' . $member->nom . ' is already scheduled for another soutenance on the specified date';

                return response()->json(['error' => $errorMessage, 'code' => 3], 422);
            }
        }

        // Create the soutenance record
        $soutenance = Soutenance::create([
            'date' => date('Y-m-d', strtotime($dateTime)),
            'heure' => date('H:i:s', strtotime($dateTime)),
            'etat' => 'scheduled', // Set initial state
            'id_localisation' => $localisationId,
        ]);
        Log::info('soutenance id : ' . $soutenance->id);

        // Create an entry in the 'effectuer' table to link doctorant with soutenance
        Effectuer::create([
            'id_soutenance' => $soutenance->id,
            'id_doctorant' => $doctorantId,
            'numero_ordre' => $numeroDordre,
        ]);

        // Process jury members and their roles
        foreach ($juryMembers as $juryMember) {
            $memberId = $juryMember['memberId'];
            $memberRole = $juryMember['memberRole'];

            // Create an 'evaluer' record for this member and soutenance
            Evaluer::create([
                'id_membre' => $memberId,
                'id_soutenance' => $soutenance->id,
                'qualite' => $memberRole,
            ]);
        }

        // Redirect to a success page or return a response
        return response()->json(['message' => 'Soutenance created successfully'], 200);
    }












    
    public function getLocalisations()
    {
        $localisations = Localisation::all();
        return response()->json($localisations);
    }
    public function searchMembers(Request $request)
    {
        $query = $request->input('query');

        $members = Membre::where('nom', 'like', "%$query%")
                        ->orWhere('prenom', 'like', "%$query%")
                        ->get();

        return response()->json($members);
    }






    /**
     * Get a list of all doctorants with scheduled soutenances.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorantsWithSoutenance()
    {
        // Fetch doctorants with their associated soutenances
        // $doctorants = Doctorant::whereHas('soutenances')
        //     ->with(['soutenances' => function($query) {
        //         $query->with('localisation'); 
        //     }])
        //     ->get();
        $doctorants = Doctorant::whereHas('soutenances')
        ->with(['soutenances' => function($query) {
            $query->orderBy('date', 'desc')->with('localisation'); 
        }])
        ->get();
            // dd($doctorants);
        // Check if doctorants collection is not empty
        if ($doctorants->isNotEmpty()) {
            // Return view with doctorants data
            return view('listdessoutenances', compact('doctorants'));
        } else {
            // Handle case where no doctorants match the criteria
            return view('listdessoutenances', ['message' => 'No doctorants found matching the criteria.']);
        }
    }
    

    /**
     * Get all soutenances for the specified school year.
     *
     * @param string $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSoutenancesForYear($year)
    {
        // Fetch soutenances for the specified school year
        $soutenances = Soutenance::where('school_year', $year)->get();

        return response()->json($soutenances);
    }





    public function show($doctorantId)
    {
        // Retrieve doctorant details with theses, soutenances, their localisations, and jury members
        $doctorant = Doctorant::with([
            'theses',
            'soutenances.localisation',
            'soutenances.juryMembers' => function ($query) {
                $query->with(['faculte', 'autre'])->withPivot('qualite');
            }
        ])->findOrFail($doctorantId);
        // Sort the jury members so that "Président" appears first
        foreach ($doctorant->soutenances as $soutenance) {
            $soutenance->juryMembers = $soutenance->juryMembers->sortByDesc(function ($member) {
                return $member->pivot->qualite === 'Président';
            })->values();
        }

        return view('informationdusoutenance', compact('doctorant'));
    }


 
    public function edit($doctorantId)
    {
        // Retrieve doctorant details with theses, soutenances, their localisations, and jury members
        $doctorant = Doctorant::with([
            'theses',
            'soutenances.localisation',
            'soutenances.juryMembers' => function ($query) {
                $query->with(['faculte', 'autre'])->withPivot('qualite');
            }
        ])->findOrFail($doctorantId);

        // Retrieve localisations from the database
        $localisations = Localisation::all();

        // Retrieve list of members
        $members = Membre::select('id', 'nom', 'prenom', 'sex')->get();

        // Define the roles array for jury members
        $roles = [
            'Président',
            'Président/rapporteur',
            'Directeur de thèse',
            'Examinateur',
            'Rapporteur',
            'Co_directeur',
            "Invité",
        ];

        $lastNumeroOrdre = $this->getLastNumeroOrdre();

        // Format date and time for the 'datetime-local' input
        $soutenance = $doctorant->soutenances->first();
        $formattedDateTime = $soutenance->date . 'T' . substr($soutenance->heure, 0, 5); // Format as 'YYYY-MM-DDTHH:MM'

        // Pass data to the view
        return view('modifierinformationdusoutenance', [
            'doctorant' => $doctorant,
            'localisations' => $localisations,
            'members' => $members,
            'lastNumeroOrdre' => $lastNumeroOrdre,
            'roles' => $roles,
            'formattedDateTime' => $formattedDateTime, // Pass the formatted datetime
        ]);
        
    }






    public function update(Request $request)
    {
        
        
        // Validate the request data
        $request->validate([
            'doctorantId' => 'required|exists:doctorants,id',
            'soutenancesId'=> 'required|exists:soutenances,id',
            'date_time' => 'required|date_format:Y-m-d\TH:i',
            'localisation' => 'required|exists:localisations,id',
            'juryMembers' => 'required|array|min:5',
            'juryMembers.*.memberId' => 'required|exists:membres,id',
            'juryMembers.*.memberRole' => 'required|string',
            'numeroDordre' => 'required|integer',
        ]);
        
        // Get the raw request body as JSON
        $jsonData = $request->getContent();
        // Decode the JSON data
        $data = json_decode($jsonData, true);

        // Extract form data
        $doctorantId = $data['doctorantId'];
        $soutenancesId = $data['soutenancesId'];
        $dateTime = $data['date_time'];
        $localisationId = $data['localisation'];
        $numeroDordre = $data['numeroDordre'];
        $juryMembers = $data['juryMembers'];

        // Find the soutenance by ID
        $soutenance = Soutenance::findOrFail($soutenancesId);

        // Check if the selected localisation is available for the whole day on the specified date
        $existingSoutenance = Soutenance::where('id_localisation', $localisationId)
            ->whereDate('date', date('Y-m-d', strtotime($dateTime)))
            ->where('id', '!=', $soutenance->id)
            ->first();

        if ($existingSoutenance) {
            return response()->json(['error' => 'Location already booked for the specified date', 'code' => 1], 422);
        }

        $existingNumeroDorder = Effectuer::where('numero_ordre', $numeroDordre)
            ->where('id_soutenance', '!=', $soutenance->id)
            ->exists();

        if ($existingNumeroDorder) {
            return response()->json(['error' => 'Duplicate order number', 'code' => 2], 422);
        }

        // Check if any jury member is part of another soutenance on the same day
        foreach ($juryMembers as $juryMember) {
            $memberId = $juryMember['memberId'];

            $existingJurySoutenance = Evaluer::whereHas('soutenance', function ($query) use ($dateTime) {
                $query->whereDate('date', date('Y-m-d', strtotime($dateTime)));
            })
            ->where('id_membre', $memberId)
            ->where('id_soutenance', '!=', $soutenance->id)
            ->exists();

            if ($existingJurySoutenance) {
                $member = Membre::findOrFail($memberId);
                $errorMessage = 'Jury member ' . $member->prenom . ' ' . $member->nom . ' is already scheduled for another soutenance on the specified date';

                return response()->json(['error' => $errorMessage, 'code' => 3], 422);
            }
        }

        // Update the soutenance record
        $soutenance->update([
            'date' => date('Y-m-d', strtotime($dateTime)),
            'heure' => date('H:i:s', strtotime($dateTime)),
            'etat' => 'scheduled',
            'id_localisation' => $localisationId,
        ]);

        // Update the 'effectuer' record
        $effectuer = Effectuer::where('id_soutenance', $soutenance->id)->first();
        $effectuer->update([
            'id_doctorant' => $doctorantId,
            'numero_ordre' => $numeroDordre,
        ]);

        // Update jury members
        Evaluer::where('id_soutenance', $soutenance->id)->delete();

        foreach ($juryMembers as $juryMember) {
            Evaluer::create([
                'id_membre' => $juryMember['memberId'],
                'id_soutenance' => $soutenance->id,
                'qualite' => $juryMember['memberRole'],
            ]);
        }

        return response()->json(['message' => 'Soutenance updated successfully'], 200);
    }







}
