<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Membre;
use App\Models\These;

class Valider extends Model
{
    use HasFactory;
    protected $table = 'valider';
    public $timestamps = false;
    protected $fillable = ['avis','lien_rapport','id_utilisateur','id_membre','id_these'];

    // Définir les relations
    public function membre()
    {
        return $this->belongsTo(Membre::class, 'id_membre');
    }

    public function these()
    {
        return $this->belongsTo(These::class, 'id_these');
    }
    public function rapporteur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
    


}
