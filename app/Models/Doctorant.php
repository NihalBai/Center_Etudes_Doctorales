<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Membre;
use App\Models\These;
use App\Models\Effectuer;

 // Importez le modèle Membre ici

class Doctorant extends Model
{
    use HasFactory;

    protected $table = 'doctorants';
    public $timestamps = false;

    protected $fillable = [
        'nom', 'prenom', 'CINE', 'sex', 'lieu',  'date_de_naissance',  'id_encadrant','photo_path', 'email','tele','discipline','dossier'
        
        
    ];

    // Relation avec l'encadrant
    public function encadrant()
    {
        return $this->belongsTo(Membre::class, 'id_encadrant');
    }


    public function these()
    {
        return $this->hasOne(These::class, 'id_doctorant');
    }
    public function files()
    {
        return $this->hasMany(File::class, 'doctorant_id');
    }
    //les scolarites du doctorant
    public function scolarites()
    {
        return $this->hasMany(Scolarite::class, 'id_doctorant');
    }

    // Define the relationship with the Scolarite model
    
    
    public function theses()
    {
        return $this->hasOne(These::class, 'id_doctorant');
    }

 
    // public function soutenances()
    // {
    //     return $this->belongsToMany(Soutenance::class, 'effectuer', 'id_doctorant', 'id_soutenance');
    // }
    public function soutenances()
    {
        return $this->belongsToMany(Soutenance::class, 'effectuer', 'id_doctorant', 'id_soutenance')
        ->withPivot('numero_ordre')
        ->withTimestamps();
    }

    // Custom method to get the first (and only) soutenance
    // public function soutenance()
    // {
    //      return $this->soutenances()->first();
    // }
    

    
    // Define the encadrant_id relationship
    public function encadrant_id()
    {
        return $this->belongsTo(Membre::class, 'id_encadrant');
    }

    public function rapporteurs()
    {
        return $this->hasManyThrough(
            Membre::class, // Target model (Membre)
            Evaluer::class, // Intermediate model (Evaluer)
            'id_soutenance', // Foreign key on Evaluer (id_soutenance)
            'id', // Foreign key on Membre (id)
            'id_membre', // Local key on Evaluer (id_membre)
            'id' // Local key on Membre (id)
        )->where('qualite', 'Rapporteur'); // Additional condition for qualite column
    }
    public function effectuer()
    {
        return $this->hasOne(Effectuer::class, 'id_doctorant', 'id');
    }

    public function juryMembers()
    {
        return $this->hasManyThrough(
            Membre::class,
            Soutenance::class,
            'id', // Foreign key on the soutenances table...
            'id', // Foreign key on the membres table...
            'id', // Local key on the doctorants table...
            'id_soutenance' // Local key on the evaluer table...
        )->join('evaluer', 'evaluer.id_membre', '=', 'membres.id')
          ->select('membres.*', 'evaluer.qualite');
    }
    public function doctorantArabe()
    {
        return $this->hasOne(DoctorantArabe::class, 'id');
    }





    



}
