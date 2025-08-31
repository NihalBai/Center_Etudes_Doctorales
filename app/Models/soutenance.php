<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soutenance extends Model
{
    use HasFactory;
    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class, 'id_doctorant');
    }


    public function evaluer()
    {
        return $this->hasMany(Evaluer::class, 'id_soutenance');
    }

    public function effectuer()
    {
        return $this->hasMany(Effectuer::class, 'id_soutenance');
    }

    public function resultat()
    {
        return $this->hasOne(Resultat::class, 'id_soutenance');
    }


    public function encadrant()
    {
        return $this->belongsTo(Membre::class, 'id_encadrant');
    }
    protected $table = 'soutenances';


    protected $fillable = [
        
        'date',// Add 'date' to the fillable array
        'heure', 
        'etat',
        'id_localisation',
        // Add other fields here as needed
    ];

    // public function doctorant()
    // {
    //     return $this->belongsTo(Doctorant::class, 'id_doctorant');
    // }


    public function getDirector()
    {
        return $this->evaluer()->where('qualite', 'Directeur de thèse')->first()->membre;
    }

    public function getRapporteurs()
    {
        return $this->evaluer()->where('qualite', 'Rapporteur')->with('membre')->get()->pluck('membre');
    }

    public function addJuryMember($membreId, $qualite)
    {
        // Add a member to the jury for this soutenance with a specific role
        $this->evaluer()->create([
            'id_membre' => $membreId,
            'qualite' => $qualite,
        ]);
    }

    public function getJuryMembers($qualite = null)
    {
        // Retrieve all jury members for this soutenance
        $query = $this->evaluer();
        
        if ($qualite) {
            $query->where('qualite', $qualite);
        }

        return $query->with('membre')->get()->pluck('membre')->unique('id');
    }
    public function assignJuryFromValider()
    {
        $theses = $this->doctorant->theses;

        foreach ($theses as $these) {
            $valider = $these->valider;

            foreach ($valider as $v) {
                // Determine the role (qualite) based on avis
                $qualite = ($v->avis === 'accepté') ? 'Rapporteur' : null; // Assuming 'accepté' means rapporteur

                if ($qualite) {
                    // Add member to the jury with determined role
                    $this->evaluer()->create([
                        'id_membre' => $v->id_membre,
                        'qualite' => $qualite,
                    ]);
                }
            }
        }
    }


    public function localisation()
    {
        return $this->belongsTo(Localisation::class, 'id_localisation');
    }

    public function resultats()
    {
        return $this->hasOne(Resultat::class, 'id_soutenance');
    }
    

    public function membres()
    {
        return $this->belongsToMany(Membre::class, 'evaluer', 'id_soutenance', 'id_membre')
                    ->withPivot('qualite');
    }
    // public function doctorants()
    // {
    //     return $this->belongsToMany(Doctorant::class, 'effectuer', 'id_soutenance', 'id_doctorant');
    // }
    public function doctorants()
    {
        return $this->belongsToMany(Doctorant::class, 'effectuer', 'id_soutenance', 'id_doctorant')
                    ->withPivot('numero_ordre')
                    ->withTimestamps();
    }
    public function juryMembers()
    {
        return $this->belongsToMany(Membre::class, 'evaluer', 'id_soutenance', 'id_membre')
                    ->withPivot('qualite')
                    ->withTimestamps();
    }



    
    public function theses()
    {
        return $this->belongsToMany(These::class, 'effectuer', 'id_soutenance', 'id_doctorant')
                    ->withTimestamps();
    }
    

  

   

    public function autre_relation()
    {
        return $this->belongsTo(Autre::class, 'autre_id');
    }
   

 
    
    

    
    
   

    


   



}
