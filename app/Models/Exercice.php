<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercice extends Model
{
    use HasFactory;

    protected $table = 'exercice'; // Nom de la table dans la base de données

    protected $fillable = [
        'DEMANDE_CONGE_ID',
        'DROIT_AU_CONGE_ID',
        'JOURS_PRIS',
        // autres champs remplissables
    ];

    // Relation avec Demande
    public function demande()
    {
        return $this->belongsTo('App\Models\Demande', 'DEMANDE_CONGE_ID');
    }

    // Relation avec DroitConge
    public function droitConge()
    {
        return $this->belongsTo('App\Models\DroitConge', 'DROIT_AU_CONGE_ID');
    }
}
