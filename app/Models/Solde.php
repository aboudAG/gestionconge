<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solde extends Model
{
    use HasFactory;

    protected $table = 'droit_au_conges';

    protected $fillable = [
        'EMPLOYE_ID',
        'ANNEE',
        'JOURS_PRIS',
        'JOURS_RESTANT'
    ];

    // Relation avec le modèle Employe
    public function employe()
    {
        return $this->belongsTo(Employe::class, 'EMPLOYE_ID', 'MATRICULE');
    }
}
