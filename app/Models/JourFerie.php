<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourFerie extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow Laravel's naming convention
    protected $table = 'jour_ferie';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'ID';

    // Specify which attributes can be mass-assigned
    protected $fillable = ['JOUR', 'DESIGNATION'];

    // If the table does not have timestamps (created_at and updated_at)
    public $timestamps = false;

    // Define the date casting for the JOUR attribute
    protected $casts = [
        'JOUR' => 'date',
    ];
}