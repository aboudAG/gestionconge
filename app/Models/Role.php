<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'NOM',
        'Description',
    ];

    protected $dates = [
        'CREATED_AT',
        'UPDATED_AT',
    ];


    public function employes()
    {
        return $this->hasMany(Employe::class);
    }


}
