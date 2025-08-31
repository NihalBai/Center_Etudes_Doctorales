<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autre extends Model
{
    protected $fillable = ['nom', 'ville'];
    public $timestamps = false;
    use HasFactory;

    public function membres()
    {
        return $this->hasMany(Membre::class, 'id_autre');
    }
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
