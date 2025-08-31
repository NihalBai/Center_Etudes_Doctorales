<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scolarite extends Model
{
    use HasFactory;
    // Define fillable properties
    protected $fillable = [
        'niveau',
        'specialite',
        'mois',
        'annee',
        'mention',
        'id_doctorant'
    ];

    // Define the relationship with the Doctorant model
    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class, 'id_doctorant');
    }
}
