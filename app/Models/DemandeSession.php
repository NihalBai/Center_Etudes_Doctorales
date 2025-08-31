<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeSession extends Model
{
    use HasFactory;
    protected $table = 'demande_sessions';
    

    protected $fillable = ['date','pv_global_signe'];

     // Relation avec les demandes associées à cette session
    public function demande()
    {
         return $this->hasMany(Demande::class, 'id_session');
    }
}