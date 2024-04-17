<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Employe extends Model
{
    use HasFactory;

    protected $table = 'employes';

    protected $primaryKey = 'MATRICULE';

    protected $fillable = [
        'MATRICULE',
        'NOM',
        'PRENOM',
        'POSTE',
        'STRUCTURE_ID',
        'ROLE_ID',
        'DATE_EMBAUCHE',

    ];



    public function structure()
    {
        return $this->belongsTo(Structure::class, 'STRUCTURE_ID');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'ROLE_ID');
    }
}
