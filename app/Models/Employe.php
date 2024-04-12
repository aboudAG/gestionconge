<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employe extends Model
{
    use HasFactory;

    use HasFactory, Notifiable;

    protected $table = 'employes';

    protected $primaryKey = 'matricule';


    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'poste',
        'structure_id',
        'role_id',
        'email',
        'password',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'email_verified_at',
        'date_embauche',
        'created_at',
        'updated_at',

    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Si vous utilisez des relations, définissez-les ici. Par exemple :
    public function structure()
    {
        return $this->belongsTo(Structure::class, 'structure_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Vous pouvez également vouloir ajouter une méthode pour vérifier le mot de passe si vous utilisez cela pour l'authentification.
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Vous pouvez aussi surcharger le nom d'utilisateur pour l'authentification si nécessaire.
    public function getUsernameForAuthentication()
    {
        return $this->matricule;
    }
}
