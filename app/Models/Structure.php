<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    use HasFactory;

    protected $table = 'structures';

    protected $fillable = [
        'CODE',
        'NOM',
        'TYPE',
        'PARENT_ID',
        'CHEMIN',

    ];

    protected $dates = [
        'CREATED_AT',
        'UPDATED_AT',
    ];

   


    public function employes()
    {
        return $this->hasMany(Employe::class, 'STRUCTURE_ID');
    }

    public function parent()
    {
        return $this->belongsTo(Structure::class, 'PARENT_ID');
    }

    public function children()
    {
        return $this->hasMany(Structure::class, 'PARENT_ID', 'ID');
    }
   
}


