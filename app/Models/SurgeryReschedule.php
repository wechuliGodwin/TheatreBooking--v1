<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurgeryReschedule extends Model
{
    protected $fillable = [
        'surgery_id',
        'previous_date_of_surgery',
        'previous_surgery',
        'previous_surgeon',
        'previous_surgery_type',
        'previous_surgery_category',
        'previous_sha_procedure',
        'previous_case_order',
        'previous_theatre_room',
        'reason',
        'rescheduled_by',
    ];

    protected $casts = [
        'previous_date_of_surgery' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function surgery()
    {
        return $this->belongsTo(Surgeries::class, 'surgery_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'rescheduled_by');
    }
}