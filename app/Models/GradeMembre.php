<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeMembre extends Model
{   
    protected $table = 'grade_membre';
    use HasFactory;
    protected $fillable = [
        
        'membre_id',
        'grade_id', 
    ];
}
