<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

     // Définir le nom de la table si ce n'est pas la convention Laravel
     protected $table = 'notifications';

     // Les attributs qui sont mass assignable
     protected $fillable = [
         'MESSAGE',
         'DATE_ENVOIE',
         'EMPLOYE_ID',
     ];

     // Les attributs qui doivent être castés à des types natifs
     protected $casts = [
         'DATE_ENVOIE' => 'datetime',
     ];

     // Relation avec le modèle Employe
     public function employe()
     {
         return $this->belongsTo(Employe::class, 'EMPLOYE_ID', 'MATRICULE');
     }
}
