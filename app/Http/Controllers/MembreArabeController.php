<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembreArabe;
class MembreArabeController extends Controller
{
    public function store(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'id' => 'required|array',
        'id.*' => 'required|exists:membres,id',
        'nom' => 'required|array',
        'nom.*' => 'required|string',
        'prenom' => 'required|array',
        'prenom.*' => 'required|string',
        'grade' => 'required|array',
        'grade.*' => 'required|string',
        'qualite' => 'required|array',
        'qualite.*' => 'required|string',
    ]);

    // Loop through the submitted data
    foreach ($validatedData['id'] as $key => $id) {
        // Check if a member Arab information already exists
        $membreArabe = MembreArabe::where('id', $id)->first();

        // If exists, update the existing record
        if ($membreArabe) {
            $membreArabe->update([
                'nom' => $validatedData['nom'][$key],
                'prenom' => $validatedData['prenom'][$key],
                'grade' => $validatedData['grade'][$key],
                'qualite' => $validatedData['qualite'][$key],
            ]);
        } else {
            // Otherwise, create a new record
            MembreArabe::create([
                'id' => $id,
                'nom' => $validatedData['nom'][$key],
                'prenom' => $validatedData['prenom'][$key],
                'grade' => $validatedData['grade'][$key],
                'qualite' => $validatedData['qualite'][$key],
            ]);
        }
    }

    // Redirect back to the previous page with a success message
    return redirect()->back()->with('success', 'Informations enregistrées avec succès.');
}


}
