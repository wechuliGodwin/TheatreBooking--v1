<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surgeries extends Model
{
    protected $table = 'surgeries';

    protected $fillable = [
        'session_number',
        'session_date',
        'theatre_request_date',
        'full_name',
        'patient_number',
        'phone_numbers',
        'theatre_room',
        'age',
        'scheduling_status',
        'booking_status',
        'notes_comments',
        'diagnosis',
        'surgery',
        'urgent_cancer',
        'surgery_type',
        'surgery_category',
        'proposed_date_of_surgery',
        'date_of_surgery',
        'payment_type',
        'sha_procedure',
        'sha_code',
        'cpt_code',
        'sha_approved_amount',
        'sha_expiry_date',
        'secondary_payer',
        'second_payer_approved_amount',
        'length_of_surgery',
        'date_deposit_paid',
        'special_needs',
        'department',
        'surgeon',
        'deposit_amount',
        'post_op_location',
        'requires_anesthesia_clearance',
        'anesthesia_clearance_notes',
        'case_order',
        'sha_eligible',
        'icd10_code',
        'department_additional',
        'second_surgeon',
        'attachments',
        'appointment_id',
        'entry_date',
        'cancellation_reason',
        'cancellation_type',
        'cancelled_at',
    ];

    protected $casts = [
        'scheduling_status' => 'string',
        'payment_type' => 'string',
        'secondary_payer' => 'string',
        'post_op_location' => 'string',
        'theatre_room' => 'string',
        'department' => 'string',
        'surgery_type' => 'string',
        'department_additional' => 'string',
        'urgent_cancer' => 'boolean',
        'requires_anesthesia_clearance' => 'boolean',
        'sha_eligible' => 'boolean',
        'session_date' => 'date',
        'theatre_request_date' => 'date',
        'proposed_date_of_surgery' => 'date',
        'date_of_surgery' => 'date',
        'sha_expiry_date' => 'date',
        'date_deposit_paid' => 'date',
        'entry_date' => 'date',
        'cancelled_at' => 'date',
    ];
    public function reschedules()
    {
        return $this->hasMany(SurgeryReschedule::class, 'surgery_id');
    }
}