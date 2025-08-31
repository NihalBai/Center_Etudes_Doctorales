<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutreController extends Controller
{
    //
    public static function search(array $attributes)
    {
        $query = static::query();

        // Add conditions based on the provided attributes
        foreach ($attributes as $key => $value) {
            if ($value !== null) {
                $query->where($key, $value);
            }
        }

        return $query;
    }
}
