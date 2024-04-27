<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DroitConge extends Model
{
    use HasFactory;

    protected $table = 'droit_au_conges'; // Assurez-vous que le nom de la table est correct

    protected $fillable = [
        'EMPLOYE_ID',
        'ANNEE',
        'JOURS_PRIS',
        'JOURS_RESTANT'
    ];

    // Si vous utilisez des clés primaires non numériques ou un nom de clé primaire différent de 'id'
    protected $primaryKey = 'ID'; // Assurez-vous que cela correspond au nom de votre colonne ID dans la table


    /**
     * Relation à l'employé
     */
    public function employe()
    {
        return $this->belongsTo(Employe::class, 'EMPLOYE_ID');
    }
}
