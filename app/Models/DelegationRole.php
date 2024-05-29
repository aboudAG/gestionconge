<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelegationRole extends Model
{
    use HasFactory;

    protected $table = 'delegation_role';

    protected $fillable = [
        'EMPLOYE_ID',
        'DATE_DEBUT',
        'DATE_FIN',
        'EMPLOYE_DELEGUEUR_ID',
        'ROLE_ID',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class, 'EMPLOYE_ID', 'MATRICULE');
    }

    public function delegueur()
    {
        return $this->belongsTo(Employe::class, 'EMPLOYE_DELEGUEUR_ID', 'MATRICULE');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'ROLE_ID', 'ID');
    }
}