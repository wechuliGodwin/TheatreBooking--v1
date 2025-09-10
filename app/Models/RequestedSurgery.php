<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestedSurgery extends Model
{
    protected $connection = 'mysql_remote';
    protected $table = 'bk_appointments'; // or whatever the table name is

    // The remote table doesn't have timestamps
    public $timestamps = false;

    // Define the fillable properties that correspond to the remote table schema
    protected $fillable = [
        'appointment_number',
        'full_name',
        'patient_number',
        'email',
        'phone',
        'specialization',
        // Add any other fields you want to read
    ];
}
