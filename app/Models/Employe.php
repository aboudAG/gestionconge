<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Employe extends Model
{
    use HasFactory;

    protected $table = 'employes';

    protected $primaryKey = 'MATRICULE';


    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'MATRICULE',
        'NOM',
        'PRENOM',
        'POSTE',
        'STRUCTURE_ID',
        'ROLE_ID',

    ];


    protected $dates = [
        'DATE_EMBAUCHE',
        'created_at',
        'updated_at',

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
