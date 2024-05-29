<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Demande extends Model
{
    use HasFactory;

    protected $table = 'demandes_conges';

    protected $fillable = [
        'EMPLOYE_ID',
        'TYPE_DEMANDE',
        'TITRE',
        'DATE_DEBUT',
        'DATE_FIN',
        'EMPLOYE_REMPLACEMENT_ID',
        'DATE_CREATION',
    ];

    protected $casts = [
        'DATE_DEBUT' => 'datetime',
        'DATE_FIN' => 'datetime',
    ];
    // Relation avec le modèle Employe
    public function employe()
    {
        return $this->belongsTo(Employe::class, 'EMPLOYE_ID');
    }

    public function type()
{
    return $this->belongsTo(Type::class, 'TYPE_DEMANDE', 'ID');
}

    public function statuts()
    {
        return $this->hasMany(StatutConge::class, 'DEMANDE_CONGE_ID', 'ID');
    }

    public function etapes()
    {
        return $this->hasMany(Etape::class, 'DEMANDE_CONGE_ID', 'ID');
    }
    public function remplacant()
    {
        return $this->belongsTo(Employe::class, 'EMPLOYE_REMPLACEMENT_ID', 'MATRICULE');
    }




    public function currentEtape()
    {
        $statutEnAttente = $this->statuts()
                        ->where('STATUT', 'En Attente')
                        ->first();

        if ($statutEnAttente) {
            return Etape::find($statutEnAttente->ETAPE_ID);
        }

        return null;
    }

   
        public function getNextEtape($currentEtape) {
            $progression = [
                'Service' => 'Departement',
                'Departement' => 'Direction',
                'Direction' => 'RH',
                
            ];
    
            return $progression[$currentEtape] ?? null;
        }
    }



