<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Universite;

class UniversiteController extends Controller
{
    //
    public function getByCity($city_id)
{
    $universities = Universite::where('city_id', $city_id)->get();
    return response()->json($universities);
}
}
