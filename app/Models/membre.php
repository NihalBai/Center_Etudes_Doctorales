<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    //
    use HasFactory;
    protected $table = 'membres';
    protected $fillable = [
        'nom', 'prenom', 'email', 'sex', 'tele', 'grade', 'autre_id' ,'faculte_id'
    ];


    public function universite()
    {
        return $this->belongsTo(Universite::class);
    }
    public function getNameAttribute()
    {
        $affiliation = '';

            if ($this->id_faculte != null) {
                $faculte = Faculte::find($this->id_faculte);
                if ($faculte) {
                    $affiliation .= $faculte->nom ;

                    // Check if the faculte has a linked universite
                    if ($faculte->id_universite != null) {
                        $universite = Universite::find($faculte->id_universite);
                        if ($universite) {
                            $affiliation .= ' , ' . $universite->nom. ', ' . $faculte->ville;
                        }
                    }
                }
            }
        else{
            if ($this->id_autre != null) {
                $autre = Autre::find($this->id_autre);
                if ($autre) {
                    $affiliation .= $autre->nom . ', ' . $autre->ville;
                }
            }}

            return $affiliation;
    }
    
    public function validations()
    {
        return $this->hasMany(Valider::class, 'id_membre');
    }
    
    public function soutenances()
    {
        return $this->belongsToMany(Soutenance::class, 'evaluer', 'id_membre', 'id_soutenance')
            ->withPivot('qualite');
    }
    public function faculte()
    {
        return $this->belongsTo(Faculte::class, 'id_faculte');
    }

    public function autre()
    {
        return $this->belongsTo(Autre::class, 'id_autre');
    }
    

    public function membreArabe()
    {
        return $this->hasOne(MembreArabe::class,'id');
    }
    public function soutenancesEvaluees()
    {
        return $this->belongsToMany(Soutenance::class, 'evaluer', 'id_membre', 'id_soutenance')
                    ->withPivot('qualite');
    }

    public function thesesValidees()
    {
        return $this->belongsToMany(These::class, 'valider', 'id_membre', 'id_these')
                    ->withPivot('avis', 'lien_rapport', 'id_utilisateur');
    }



    public function grade()
    {
        return $this->belongsTo(Grade::class, 'id_grade');
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'grade_membre', 'membre_id', 'grade_id')->withTimestamps();
    }
    public function latestGrade()
    {
        return $this->grades()->latest()->first();
    }
    public function getGradeBefore($date)
    {
        return $this->grades()
                    ->wherePivot('created_at', '<', $date)
                    ->orderBy('pivot_created_at', 'desc')
                    ->first();
    }
    public function theses()
    {
        return $this->belongsToMany(These::class, 'valider', 'id_membre', 'id_these')
                    ->withPivot('avis', 'lien_rapport', 'id_utilisateur');
    }
    
    public function encadrantSoutenances()
    {
        return $this->hasMany(Soutenance::class, 'id_encadrant', 'id');
    }
    
    
    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class, 'id_docotrant');
    }
    
    //Table VAlider
    public function valider()
    {
        return $this->hasMany(Valider::class, 'id_membre');
    }
    //Table Evaluer
    public function evaluer()
    {
        return $this->hasMany(Evaluer::class, 'id_membre');
    }
    
    
    

    
   
   


    
    

    

}
