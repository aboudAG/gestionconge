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
        'TYPE_ID',
        'TITRE',
        'DATE_DEBUT',
        'DATE_FIN',
        'EMPLOYE_REMPLACEMENT_ID',
        'DATE_CREATION',
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
}
