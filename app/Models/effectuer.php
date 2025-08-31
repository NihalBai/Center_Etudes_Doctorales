<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Soutenance; 
use App\Models\Doctorant; 

class Effectuer extends Model
{
    use HasFactory;
    protected $table = 'effectuer';
    protected $fillable = [
        'id_soutenance', // Add any other fillable fields here
        'id_doctorant',
        'numero_ordre',
    ];
    
    public function soutenance()
    {
        return $this->belongsTo(Soutenance::class, 'id_soutenance');
    }
    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class,'id_doctorant');
    }

    public function resultat()
    {
        return $this->hasOne(Resultat::class, 'id_soutenance');
    }


}
