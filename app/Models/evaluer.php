<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Soutenance;
use App\Models\Membre;

class Evaluer extends Model
{
    use HasFactory;
    protected $table = 'evaluer';
    protected $fillable = [
        'id_membre',
        'id_soutenance',
        'qualite',
    ];
    public function soutenance()
    {
        return $this->belongsTo(Soutenance::class, 'id_soutenance');
    }

    
    public function membre()
    {
        return $this->belongsTo(Membre::class, 'id_membre');
    }
}