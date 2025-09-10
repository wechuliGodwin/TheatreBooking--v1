<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surgeries extends Model
{
    // This model will use the default database connection (your local db)
    protected $table = 'local_surgeries'; // or whatever the table name is

    // Define the fillable properties that correspond to your local table schema
    protected $fillable = [
        'FullName',
        'MRN',
        'PhoneNumbers', // You'll need to pull this from the remote DB somehow or add it manually
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
}
