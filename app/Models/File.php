<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctorant;

class File extends Model
{
    use HasFactory;
    protected $table = 'files';
    public $timestamps = false;

    protected $fillable = ['doctorant_id', 'type', 'path'];

    public function doctorant()
    {
        return $this->belongsTo(Doctorant::class,'doctorant_id');
    }
    
}