<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    protected $table = 'affiliations'; // Specify the table name if different from 'affiliations'

    protected $fillable = ['universite', 'faculte', 'ville', 'autre'];


    
    // Defin relation with Membre
    public function membres()
    {
        return $this->hasMany(Membre::class);
    }

   
    // public function getNameAttribute()
    // {
    //     $v = $this->universite . ' - ' . $this->faculte ;
    //     if($this->autre!=null){
    //         $v = $this->autre;
    //     }
    //     $v.= ', ' . $this->ville;
    //     return $v;
    //     // return $this->universite . ' - ' . $this->faculte . ', ' . $this->ville . ', ' . $this->autre;
    // }

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

    use HasFactory;
}

