<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorantArabe extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'discipline',
        'specialite',
    ];

    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class, 'id');
    }








}
