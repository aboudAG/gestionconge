<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types_conges';

    protected $fillable = [
        'ID',
        'NOM',
        // Ajoute d'autres champs si nécessaire
    ];
}
