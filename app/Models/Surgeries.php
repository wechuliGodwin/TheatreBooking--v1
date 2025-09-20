<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surgeries extends Model
{
    protected $table = 'surgeries';

    protected $fillable = [
        'SessionNumber',
        'SessionDate',
        'TheatreRequestDate',
        'FullName',
        'PatientNumber',
        'PhoneNumbers',
        'TheatreRoom',
        'Age',
        'SchedulingStatus',
        'BookingStatus',
        'NotesComments',
        'Diagnosis',
        'Surgery',
        'UrgentCancer',
        'SurgeryType',
        'SurgeryCategory',
        'ProposedDateofSurgery',
        'DateofSurgery',
        'PaymentType',
        'SHAProcedure',
        'SHACode',
        'CPTCode',
        'SHAApprovedAmount',
        'SHAExpiryDate',
        'SecondaryPayer',
        'SecondPayerApprovedAmount',
        'LengthofSurgery',
        'DateDepositPaid',
        'SpecialNeeds',
        'Department',
        'Surgeon',
        'DepositAmount',
        'PostOpLocation',
        'RequiresAnesthesiaClearance',
        'AnesthesiaClearanceNotes',
        'CaseOrder',
        'SHAEligible',
        'ICD10Code',
        'DepartmentAdditional',
        'SecondSurgeon',
        'Attachments',
        'AppointmentId',
        'EntryDate',
        'CancellationReason',
        'CancellationType',
        'CancelledAt',
    ];

    protected $casts = [
        'SchedulingStatus' => 'string',
        'PaymentType' => 'string',
        'SecondaryPayer' => 'string',
        'PostOpLocation' => 'string',
        'TheatreRoom' => 'string',
        'Department' => 'string',
        'SurgeryType' => 'string',
        'DepartmentAdditional' => 'string',
        'UrgentCancer' => 'boolean',
        'RequiresAnesthesiaClearance' => 'boolean',
        'SHAEligible' => 'boolean',
        'SessionDate' => 'date',
        'TheatreRequestDate' => 'date',
        'ProposedDateofSurgery' => 'date',
        'DateofSurgery' => 'date',
        'SHAExpiryDate' => 'date',
        'DateDepositPaid' => 'date',
        'EntryDate' => 'date',
        'CancelledAt' => 'date',
    ];
}