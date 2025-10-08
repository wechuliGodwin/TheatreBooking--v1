@extends('layout.app')

@section('title', 'Edit Theatre Booking')

@section('content')
<style>
    /* General typography */
    body,
    .form-label,
    .section-title {
        font-family: "Segoe UI", Roboto, sans-serif;
    }

    .form-label {
        font-weight: 500;
        color: #444;
    }

    /* Inputs and selects */
    .form-control-sm,
    .form-select-sm {
        height: 34px;
        font-size: 0.85rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background-color: #fff;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s;
    }

    .form-control-sm:focus,
    .form-select-sm:focus {
        border-color: #2563eb;
        /* subtle professional blue */
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }

    /* Readonly fields */
    .readonly-field {
        background-color: #f9fafb !important;
        color: #6b7280;
        cursor: not-allowed;
    }

    /* Highlight required */
    .required-field {
        border-left: 3px solid #2563eb !important;
        /* red accent for required */
    }

    /* Section titles */
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 0.25rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #111827;
    }

    .section-title i {
        color: #2563eb;
    }

    /* Card container */
    .card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .card-body {
        padding: 1.25rem;
    }

    /* Buttons */
    .btn {
        border-radius: 6px;
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        transition: background-color 0.2s ease-in-out, color 0.2s;
    }

    .btn-primary {
        background-color: #2563eb;
        border: none;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
    }

    .btn-outline-secondary {
        border: 1px solid #d1d5db;
        color: #374151;
        background: #fff;
    }

    .btn-outline-secondary:hover {
        background-color: #f3f4f6;
        color: #111827;
    }

    .btn-warning {
        background-color: #f59e0b;
        border: none;
        color: #fff;
    }

    .btn-warning:hover {
        background-color: #d97706;
    }

    /* Alerts */
    .alert {
        font-size: 0.85rem;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
    }

    /* Tooltip */
    .tooltip-inner {
        max-width: 200px;
        font-size: 0.75rem;
    }

    /* File info */
    .file-upload-info {
        font-size: 0.8rem;
        color: #6b7280;
    }

    /* Toggle edit button */
    .edit-toggle-btn.active {
        background-color: #2563eb;
        color: #fff;
    }

    /* Mobile optimization */
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }

        .row.g-2 {
            row-gap: 0.75rem !important;
        }
    }
</style>


<div class="container-fluid p-3">
    <h5 class="card-title mb-0">Edit {{ $surgery->scheduling_status ? $surgery->scheduling_status : 'Surgery' }} surgery</h5>

    @if ($errors->any())
    <div class="alert alert-danger mb-2 p-2">
        <strong>Validation Errors:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger mb-2 p-2">
        <strong>Error:</strong> {{ session('error') }}
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success mb-2 p-2">
        <strong>Success:</strong> {{ session('success') }}
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <form action="{{ route('surgery.update', $surgery->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="editForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $surgery->id }}">

                <!-- Patient Information (Non-Collapsible) -->
                <div class="form-section mb-2"> <!-- Using layout's .form-section for professional borders/icons -->
                    <h5 class="section-title" style="color: #333;">
                        <!-- <i class="fas fa-user me-2 text-primary"></i> -->
                        Theatre Booking Details - Patient Information
                    </h5>
                    <div class="mb-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm edit-toggle-btn" id="toggleEdit">Enable Edit</button>
                    </div>
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="session_number" class="form-label small">Session Number</label>
                            <input type="text" name="session_number" id="session_number" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('session_number', $surgery->session_number ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="full_name" class="form-label small required">Full Name </label>
                            <input type="text" name="full_name" id="full_name" class="form-control form-control-sm highlight-field required-field readonly-field" value="{{ old('full_name', $surgery->full_name ?? '') }}" readonly required aria-required="true">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="patient_number" class="form-label small">Patient Number</label>
                            <input type="text" name="patient_number" id="patient_number" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('patient_number', $surgery->patient_number ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="theatre_request_date" class="form-label small">Theatre Request Date</label>
                            <input type="date" name="theatre_request_date" id="theatre_request_date" class="form-control form-control-sm highlight-field readonly-field"
                                value="{{ old('theatre_request_date', $surgery->theatre_request_date ? $surgery->theatre_request_date->format('Y-m-d') : '') }}"
                                readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="phone_numbers" class="form-label small" data-bs-toggle="tooltip" title="Enter patient's contact numbers">Phone Numbers</label>
                            <input type="text" name="phone_numbers" id="phone_numbers" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('phone_numbers', $surgery->phone_numbers ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="age" class="form-label small required">Age </label>
                            <input type="number" name="age" id="age" class="form-control form-control-sm highlight-field required-field readonly-field" value="{{ old('age', $surgery->age ?? '') }}" readonly min="0" required aria-required="true">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="scheduling_status" class="form-label small" data-bs-toggle="tooltip" title="Current scheduling status of the surgery">Scheduling Status</label>
                            <select name="scheduling_status" id="scheduling_status" class="form-select form-select-sm highlight-field readonly-field" disabled>
                                <option value="">Select Status</option>
                                @foreach (['SHA Submitted; Pending Approval', 'Insurance Approved/Deposit Paid; Ready to Schedule', 'Scheduled', 'Completed', 'Inactive', 'SHA Rejected', 'Cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('scheduling_status', $surgery->scheduling_status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Surgery Details -->
                <div class="form-section mb-2">
                    <h5 class="section-title" style="color: #333;">
                        <!-- <i class="fas fa-stethoscope me-2 text-primary"></i> -->
                        Surgery Details
                    </h5>
                    <div class="row g-2">

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="diagnosis" class="form-label small">Diagnosis</label>
                            <input type="text" name="diagnosis" id="diagnosis" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('diagnosis', $surgery->diagnosis ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="surgery" class="form-label small required">Surgery </label>
                            <input type="text" name="surgery" id="surgery" class="form-control form-control-sm highlight-field required-field readonly-field" value="{{ old('surgery', $surgery->surgery ?? '') }}" readonly required aria-required="true">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="surgery_type" class="form-label small">Surgery Type</label>
                            <select name="surgery_type" id="surgery_type" class="form-select form-select-sm highlight-field readonly-field" disabled>
                                <option value="">Select Type</option>
                                @foreach (['Elective', 'Emergency', 'Minor', 'Major'] as $type)
                                <option value="{{ $type }}" {{ old('surgery_type', $surgery->surgery_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="surgery_category" class="form-label small">Surgery Category</label>
                            <input type="text" name="surgery_category" id="surgery_category" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('surgery_category', $surgery->surgery_category ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="icd10_code" class="form-label small" data-bs-toggle="tooltip" title="International Classification of Diseases code">ICD10 Code</label>
                            <input type="text" name="icd10_code" id="icd10_code" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('icd10_code', $surgery->icd10_code ?? '') }}" readonly>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="surgeon" class="form-label small required">Surgeon </label>
                            <input type="text" name="surgeon" id="surgeon" class="form-control form-control-sm highlight-field required-field readonly-field" value="{{ old('surgeon', $surgery->surgeon ?? '') }}" readonly required aria-required="true">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="second_surgeon" class="form-label small">Second Surgeon</label>
                            <input type="text" name="second_surgeon" id="second_surgeon" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('second_surgeon', $surgery->second_surgeon ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="proposed_date_of_surgery" class="form-label small">Proposed Date of Surgery</label>
                            <input type="date" name="proposed_date_of_surgery" id="proposed_date_of_surgery" class="form-control form-control-sm highlight-field readonly-field"
                                value="{{ old('proposed_date_of_surgery', $surgery->proposed_date_of_surgery ? $surgery->proposed_date_of_surgery->format('Y-m-d') : '') }}"
                                readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="date_of_surgery" class="form-label small">Date of Surgery</label>
                            <input type="date" name="date_of_surgery" id="date_of_surgery" class="form-control form-control-sm highlight-field readonly-field"
                                value="{{ old('date_of_surgery', $surgery->date_of_surgery ? $surgery->date_of_surgery->format('Y-m-d') : '') }}"
                                readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="theatre_room" class="form-label small">Theatre Room</label>
                            <select name="theatre_room" id="theatre_room" class="form-select form-select-sm highlight-field readonly-field" disabled>
                                <option value="">Select Room</option>
                                @foreach (['Room1', 'Room2', 'Room3', 'Room4','OPERATION ROOM 1','OPERATION ROOM 2','OPERATION ROOM 3','OPERATION ROOM 4','OPERATION ROOM 5','OPERATION ROOM 6','Other'] as $room)
                                <option value="{{ $room }}" {{ old('theatre_room', $surgery->theatre_room ?? '') == $room ? 'selected' : '' }}>{{ $room }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="case_order" class="form-label small" data-bs-toggle="tooltip" title="Order of the case in the theatre schedule">Case Order</label>
                            <input type="text" name="case_order" id="case_order" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('case_order', $surgery->case_order ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="length_of_surgery" class="form-label small">Length of Surgery</label>
                            <input type="text" name="length_of_surgery" id="length_of_surgery" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('length_of_surgery', $surgery->length_of_surgery ?? '') }}" readonly placeholder="e.g., 2 hours">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="special_needs" class="form-label small">Special Needs</label>
                            <input type="text" name="special_needs" id="special_needs" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('special_needs', $surgery->special_needs ?? '') }}" readonly placeholder="e.g., Wheelchair access">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 form-check">
                            <label for="requires_anesthesia_clearance" class="form-label small">Requires Anesthesia Clearance</label>
                            <input type="checkbox" name="requires_anesthesia_clearance" id="requires_anesthesia_clearance" class="form-check-input highlight-field" {{ old('requires_anesthesia_clearance', $surgery->requires_anesthesia_clearance ?? false) ? 'checked' : '' }} value="1" disabled>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="anesthesia_clearance_notes" class="form-label small">Anesthesia Clearance Notes</label>
                            <textarea name="anesthesia_clearance_notes" id="anesthesia_clearance_notes" class="form-control form-control-sm highlight-field readonly-field" rows="2" readonly>{{ old('anesthesia_clearance_notes', $surgery->anesthesia_clearance_notes ?? '') }}</textarea>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="cpt_code" class="form-label small">CPT Code</label>
                            <input type="text" name="cpt_code" id="cpt_code" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('cpt_code', $surgery->cpt_code ?? '') }}" readonly>
                        </div>

                    </div>
                </div>

                <!-- Payment Information -->
                <div class="form-section mb-2">
                    <h5 class="section-title" style="color: #333;">
                        <!-- <i class="fas fa-credit-card me-2 text-primary"></i> -->
                        Payment Information
                    </h5>
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="payment_type" class="form-label small">Payment Type</label>
                            <select name="payment_type" id="payment_type" class="form-select form-select-sm highlight-field readonly-field" disabled>
                                <option value="">Select Payment Type</option>
                                @foreach (['Cash', 'Other Insurance', 'Compassionate', 'SHA + Other', 'SHA'] as $payment)
                                <option value="{{ $payment }}" {{ old('payment_type', $surgery->payment_type ?? '') == $payment ? 'selected' : '' }}>{{ $payment }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="sha_procedure" class="form-label small" data-bs-toggle="tooltip" title="Procedure code for SHA insurance">SHA Procedure</label>
                            <input type="text" name="sha_procedure" id="sha_procedure" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('sha_procedure', $surgery->sha_procedure ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="sha_code" class="form-label small">SHA Code</label>
                            <input type="text" name="sha_code" id="sha_code" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('sha_code', $surgery->sha_code ?? '') }}" readonly>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="sha_approved_amount" class="form-label small">SHA Approved Amount</label>
                            <input type="number" name="sha_approved_amount" id="sha_approved_amount" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('sha_approved_amount', $surgery->sha_approved_amount ?? '') }}" step="0.01" min="0" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="sha_expiry_date" class="form-label small">SHA Expiry Date</label>
                            <input type="date" name="sha_expiry_date" id="sha_expiry_date" class="form-control form-control-sm highlight-field readonly-field"
                                value="{{ old('sha_expiry_date', $surgery->sha_expiry_date ? $surgery->sha_expiry_date->format('Y-m-d') : '') }}"
                                readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="secondary_payer" class="form-label small">Secondary Payer</label>
                            <select name="secondary_payer" id="secondary_payer" class="form-select form-select-sm highlight-field readonly-field" disabled>
                                <option value="">Select Payer</option>
                                @foreach (['AAP Insurance', 'Britam Insurance', 'First Assurance Insurance', 'GA Insurance', 'Jubilee Insurance', 'None'] as $payer)
                                <option value="{{ $payer }}" {{ old('secondary_payer', $surgery->secondary_payer ?? '') == $payer ? 'selected' : '' }}>{{ $payer }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="second_payer_approved_amount" class="form-label small">Second Payer Approved Amount</label>
                            <input type="number" name="second_payer_approved_amount" id="second_payer_approved_amount" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('second_payer_approved_amount', $surgery->second_payer_approved_amount ?? '') }}" step="0.01" min="0" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="date_deposit_paid" class="form-label small">Date Deposit Paid</label>
                            <input type="date" name="date_deposit_paid" id="date_deposit_paid" class="form-control form-control-sm highlight-field readonly-field"
                                value="{{ old('date_deposit_paid', $surgery->date_deposit_paid ? $surgery->date_deposit_paid->format('Y-m-d') : '') }}"
                                readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="deposit_amount" class="form-label small">Deposit Amount</label>
                            <input type="number" name="deposit_amount" id="deposit_amount" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('deposit_amount', $surgery->deposit_amount ?? '') }}" step="0.01" min="0" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 form-check">
                            <label for="sha_eligible" class="form-label small" data-bs-toggle="tooltip" title="Check if eligible for SHA coverage">SHA Eligible</label>
                            <input type="checkbox" name="sha_eligible" id="sha_eligible" class="form-check-input highlight-field" {{ old('sha_eligible', $surgery->sha_eligible ?? false) ? 'checked' : '' }} value="1" disabled>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="notes_comments" class="form-label small">Notes/Comments</label>
                            <textarea name="notes_comments" id="notes_comments" class="form-control form-control-sm highlight-field readonly-field" rows="2" readonly>{{ old('notes_comments', $surgery->notes_comments ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="form-section mb-2">
                    <h5 class="section-title" style="color: #333;">
                        <!-- <i class="fas fa-cogs me-2 text-primary"></i> -->
                        Additional Details
                    </h5>
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="department" class="form-label small">Department</label>
                            <select name="department" id="department" class="form-select form-select-sm highlight-field readonly-field" disabled>
                                <option value="">Select Department</option>
                                @foreach (['General Surgery', 'Orthopedics', 'Neurosurgery', 'Cardiology', 'Other'] as $dept)
                                <option value="{{ $dept }}" {{ old('department', $surgery->department ?? '') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="post_op_location" class="form-label small">Post-Op Location</label>
                            <select name="post_op_location" id="post_op_location" class="form-select form-select-sm highlight-field readonly-field" disabled>
                                <option value="">Select Location</option>
                                @foreach (['Ward', 'ICU', 'Recovery Room', 'Outpatient'] as $location)
                                <option value="{{ $location }}" {{ old('post_op_location', $surgery->post_op_location ?? '') == $location ? 'selected' : '' }}>{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="department_additional" class="form-label small">Additional Department</label>
                            <select name="department_additional" id="department_additional" class="form-select form-select-sm highlight-field readonly-field" disabled>
                                <option value="">Select Additional Dept</option>
                                @foreach (['None', 'Radiology', 'Pathology', 'Anesthesiology'] as $dept)
                                <option value="{{ $dept }}" {{ old('department_additional', $surgery->department_additional ?? '') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="appointment_id" class="form-label small">Appointment ID</label>
                            <input type="text" name="appointment_id" id="appointment_id" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('appointment_id', $surgery->appointment_id ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="entry_date" class="form-label small">Entry Date</label>
                            <input type="date" name="entry_date" id="entry_date" class="form-control form-control-sm highlight-field readonly-field"
                                value="{{ old('entry_date', $surgery->entry_date ? $surgery->entry_date->format('Y-m-d') : '') }}"
                                readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="attachments" class="form-label small" data-bs-toggle="tooltip" title="Upload relevant documents (PDF, JPG, PNG)">Attachments</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-control form-control-sm highlight-field readonly-field" multiple disabled>
                            <div class="file-upload-info mt-1">No files selected</div>
                            @if ($surgery->attachments)
                            <div class="mt-1">
                                <p class="small">Existing Attachments:</p>
                                <ul class="small">
                                    @foreach (json_decode($surgery->attachments, true) ?? [] as $attachment)
                                    <li><a href="{{ Storage::url($attachment) }}" target="_blank">{{ basename($attachment) }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($surgery->cancellation_type)
                <!-- Cancellation -->
                <div class="form-section mb-2">
                    <h5 class="section-title" style="color: #333;">
                        <!-- <i class="fas fa-exclamation-triangle me-2 text-danger"></i> -->
                        Cancellation
                    </h5>
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="cancellation_reason" class="form-label small">Cancellation Reason</label>
                            <input type="text" name="cancellation_reason" id="cancellation_reason" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('cancellation_reason', $surgery->cancellation_reason ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="cancellation_type" class="form-label small">Cancellation Type</label>
                            <input type="text" name="cancellation_type" id="cancellation_type" class="form-control form-control-sm highlight-field readonly-field" value="{{ old('cancellation_type', $surgery->cancellation_type ?? '') }}" readonly>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="cancelled_at" class="form-label small">Cancelled At</label>
                            <input type="date" name="cancelled_at" id="cancelled_at" class="form-control form-control-sm highlight-field readonly-field"
                                value="{{ old('cancelled_at', $surgery->cancelled_at ? $surgery->cancelled_at->format('Y-m-d') : '') }}"
                                readonly>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" name="action" value="update" class="btn btn-primary btn-sm" disabled>Update</button>
                    @if($surgery->scheduling_status === 'Scheduled')
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#rescheduleModal">
                        Reschedule Surgery
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Retained: Reschedule Modal (unchanged, but fields now use highlight-class for consistency if needed) -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('surgery.reschedule', $surgery->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rescheduleModalLabel">Reschedule Surgery</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                        <div class="alert alert-danger mb-2 p-2">
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="mb-2">
                            <label for="date_of_surgery" class="form-label">New Date of Surgery</label>
                            <input type="date" name="date_of_surgery" id="date_of_surgery" class="form-control highlight-field"
                                value="{{ old('date_of_surgery', $surgery->date_of_surgery ? $surgery->date_of_surgery->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="mb-2">
                            <label for="surgery" class="form-label required">Surgery <span class="asterisk" title="Required">*</span></label>
                            <input type="text" name="surgery" id="surgery" class="form-control highlight-field required-field"
                                value="{{ old('surgery', $surgery->surgery) }}" required aria-required="true">
                        </div>
                        <div class="mb-2">
                            <label for="surgeon" class="form-label required">Surgeon <span class="asterisk" title="Required">*</span></label>
                            <input type="text" name="surgeon" id="surgeon" class="form-control highlight-field required-field"
                                value="{{ old('surgeon', $surgery->surgeon) }}" required aria-required="true">
                        </div>
                        <div class="mb-2">
                            <label for="surgery_type" class="form-label">Surgery Type</label>
                            <select name="surgery_type" id="surgery_type" class="form-select highlight-field" required>
                                <option value="">Select Type</option>
                                @foreach(['Elective', 'Emergency', 'Minor', 'Major'] as $type)
                                <option value="{{ $type }}" {{ old('surgery_type', $surgery->surgery_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="surgery_category" class="form-label">Surgery Category</label>
                            <input type="text" name="surgery_category" id="surgery_category" class="form-control highlight-field"
                                value="{{ old('surgery_category', $surgery->surgery_category) }}">
                        </div>
                        <div class="mb-2">
                            <label for="sha_procedure" class="form-label">SHA Procedure</label>
                            <input type="text" name="sha_procedure" id="sha_procedure" class="form-control highlight-field"
                                value="{{ old('sha_procedure', $surgery->sha_procedure) }}">
                        </div>
                        <div class="mb-2">
                            <label for="case_order" class="form-label">Case Order</label>
                            <input type="text" name="case_order" id="case_order" class="form-control highlight-field"
                                value="{{ old('case_order', $surgery->case_order) }}">
                        </div>
                        <div class="mb-2">
                            <label for="theatre_room" class="form-label">Theatre Room</label>
                            <select name="theatre_room" id="theatre_room" class="form-select highlight-field" required>
                                <option value="">Select Room</option>
                                @foreach(['Room1', 'Room2', 'Room3', 'Room4', 'OPERATION ROOM 1', 'OPERATION ROOM 2', 'OPERATION ROOM 3', 'OPERATION ROOM 4', 'OPERATION ROOM 5', 'OPERATION ROOM 6', 'Other'] as $room)
                                <option value="{{ $room }}" {{ old('theatre_room', $surgery->theatre_room) == $room ? 'selected' : '' }}>{{ $room }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="reason" class="form-label">Reason for Reschedule</label>
                            <textarea name="reason" id="reason" class="form-control highlight-field" required>{{ old('reason') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Reschedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($surgery->reschedules && $surgery->reschedules->count())
    <div class="mt-3">
        <h6>Reschedule History</h6>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Previous Date</th>
                    <th>Surgery</th>
                    <th>Surgeon</th>
                    <th>Surgery Type</th>
                    <th>Surgery Category</th>
                    <th>SHA Procedure</th>
                    <th>Case Order</th>
                    <th>Theatre Room</th>
                    <th>Reason</th>
                    <th>Rescheduled At</th>
                    <th>Rescheduled By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surgery->reschedules as $reschedule)
                <tr>
                    <td>{{ $reschedule->previous_date_of_surgery ? $reschedule->previous_date_of_surgery->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $reschedule->previous_surgery ?? 'N/A' }}</td>
                    <td>{{ $reschedule->previous_surgeon ?? 'N/A' }}</td>
                    <td>{{ $reschedule->previous_surgery_type ?? 'N/A' }}</td>
                    <td>{{ $reschedule->previous_surgery_category ?? 'N/A' }}</td>
                    <td>{{ $reschedule->previous_sha_procedure ?? 'N/A' }}</td>
                    <td>{{ $reschedule->previous_case_order ?? 'N/A' }}</td>
                    <td>{{ $reschedule->previous_theatre_room ?? 'N/A' }}</td>
                    <td>{{ $reschedule->reason }}</td>
                    <td>{{ $reschedule->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $reschedule->user->name ?? 'Unknown' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script>
    // Retained: Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Retained: File upload feedback
        const fileInput = document.getElementById('attachments');
        const fileInfo = document.querySelector('.file-upload-info');
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileInfo.textContent = `${this.files.length} file${this.files.length > 1 ? 's' : ''} selected`;
            } else {
                fileInfo.textContent = 'No files selected';
            }
        });

        // Retained: Toggle readonly for all fields
        const toggleButton = document.getElementById('toggleEdit');
        const formElements = document.querySelectorAll('#editForm input:not([type="hidden"]), #editForm select, #editForm textarea');
        const submitButton = document.querySelector('button[name="action"][value="update"]');

        toggleButton.addEventListener('click', function() {
            const isEditable = toggleButton.classList.toggle('active');
            toggleButton.textContent = isEditable ? 'Lock Form' : 'Enable Edit';
            formElements.forEach(element => {
                if (isEditable) {
                    element.removeAttribute('readonly');
                    element.removeAttribute('disabled');
                    element.classList.remove('readonly-field');
                } else {
                    if (element.type !== 'file') {
                        element.setAttribute('readonly', 'readonly');
                    }
                    element.setAttribute('disabled', 'disabled');
                    element.classList.add('readonly-field');
                }
            });
            submitButton.disabled = !isEditable;
        });
    });
</script>
@endsection