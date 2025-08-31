<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculte;
class FaculteController extends Controller
{
    //
    public function getByUniversity($university_id)
{
    $faculties = Faculte::where('university_id', $university_id)->get();
    return response()->json($faculties);
}
public function cities()
{
    $cities = Faculte::distinct()->pluck('ville');
    return response()->json($cities);
}
}
