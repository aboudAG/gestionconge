<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutConge extends Model
{
    use HasFactory;

    protected $table = 'statut_conge';

    protected $fillable = [
        'STATUT',
        'DEMANDE_CONGE_ID',
        'COMMENTAIRE',
        'APPROUVEUR_ID',
        'DATE_DECISION',
        'ETAPE_ID',
    ];

    // Définition de la relation avec le modèle DemandeConge
    public function demandeConge()
    {
        return $this->belongsTo(Demande::class, 'DEMANDE_CONGE_ID', 'ID');
    }

    public function etape()
{
    return $this->belongsTo(Etape::class, 'ETAPE_ID', 'ID');
}
}
