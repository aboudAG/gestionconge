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
        'NOM',
        'PRENOM',
        'POSTE',
        'STRUCTURE_ID',
        'ROLE_ID',
        'DATE_EMBAUCHE',

    ];



    public function structure()
    {
        return $this->belongsTo(Structure::class, 'STRUCTURE_ID','ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'MATRICULE', 'MATRICULE');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'ROLE_ID');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'EMPLOYE_ID', 'MATRICULE');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'EMPLOYE_ID', 'MATRICULE');
    }

    public function droitConges()
    {
        return $this->hasMany(DroitConge::class, 'EMPLOYE_ID', 'MATRICULE');
    }
}
