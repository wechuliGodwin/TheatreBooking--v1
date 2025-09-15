<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestedSurgery extends Model
{
    protected $connection = 'sqlsrv_remote';
    protected $table = 'bk_appointments'; // or whatever the table name is

    // The remote table doesn't have timestamps
    public $timestamps = false;

    // Define the fillable properties that correspond to the remote table schema
    protected $fillable = [

        // Add any other fields you want to read
    ];
}
