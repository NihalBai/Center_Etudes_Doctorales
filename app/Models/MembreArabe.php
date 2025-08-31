<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembreArabe extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'nom', 
        'prenom',
        'qualite',
        'grade',
    ];
    public $timestamps = false;
    public function membre()
    {
        return $this->belongsTo(Membre::class, 'id');
    }
}
