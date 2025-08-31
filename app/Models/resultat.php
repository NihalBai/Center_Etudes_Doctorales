<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultat extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'observation',
        'mention',
        'formationDoctorale',
        'specialite',
        'id_soutenance',
    ];
    use HasFactory;

    public function soutenance()
    {
        return $this->belongsTo(Soutenance::class, 'id_soutenance');
    }
    public function resultat()
    {
        return $this->belongsToMany(Resultat::class, 'effectuer','id_soutenance','id_doctorant');
    }

    public function doctorants()
    {
        return $this->belongsToMany(Doctorant::class, 'effectuer', 'id_soutenance', 'id_doctorant');
    }

    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class, 'id_soutenance');
    }

    public function effectuer()
    {
        return $this->belongsTo(Effectuer::class, 'id_soutenance');
    }
}
