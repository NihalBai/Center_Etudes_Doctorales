<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use App\Models\Doctorant;
use App\Models\Valider;
use App\Models\Demande;


class These extends Model
{
    use HasFactory;
    protected $table = 'theses';

    public $timestamps = false;
    protected $fillable = [
        'titreOriginal',
        'titreFinal',
        'formation',
        'id_doctorant',
        'acceptationDirecteur', // Ajoutez cet attribut
        'structure_recherche',
        'date_premiere_inscription',
        'nombre_publications_article',
        'nombre_publications_conference',
        'nombre_communications',
    ];

    

    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class, 'id_doctorant');
    }
    public function valider()
    {
        return $this->hasMany(Valider::class, 'id_these');
    }
    public function demande()
    {
        return $this->hasOne(Demande::class, 'id_these');
    }
    //Les theses validees par un rapporteur avec le rapport+avis+status
    public function rapporteurs()
    {
        return $this->belongsToMany(User::class, 'valider', 'id_these', 'id_utilisateur')
                    ->withPivot('avis', 'lien_rapport')
                    ->withTimestamps();
    }
    
 
    public function membres()
    {
        return $this->belongsToMany(Membre::class, 'valider', 'id_these', 'id_membre')
                    ->withPivot('avis', 'lien_rapport', 'id_utilisateur');
    }

    public function encadrant()
    {
        return $this->belongsTo(Membre::class, 'id_encadrant');
    }

    public function demandes()
    {
        return $this->hasOne(Demande::class, 'id_these');
    }




}
