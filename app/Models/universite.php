<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universite extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    public function faculte()
    {
        return $this->hasOne(Faculte::class, 'id_universite');
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
    public function facultes()
    {
        return $this->hasMany(Faculte::class, 'id_universite');
    }
}
