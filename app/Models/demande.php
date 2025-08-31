<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\These;


class Demande extends Model
{
    use HasFactory;
    protected $table = 'demandes';
    public $timestamps = false;
    protected $fillable = [
        'formation',
        'num',
        'date',
        'etat',
        'id_these',
        'id_session'
    ];

    public function these()
    {
        return $this->belongsTo(These::class, 'id_these');
    }

     // Relation avec la session de la demande
    public function session()
    {
        return $this->belongsTo(DemandeSession::class, 'id_session');
    }

}