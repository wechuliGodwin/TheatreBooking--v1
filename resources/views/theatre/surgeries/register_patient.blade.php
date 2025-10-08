@extends('layout.app')

@section('title', 'Register Theatre Booking')

@section('content')
<style>
    /* General Reset for Clean Look */
    .form-label {
        font-weight: 500;
        color: #333;
        /* Dark neutral text */
    }

    .form-control-sm,
    .form-select-sm {
        height: 34px;
        font-size: 0.9rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control-sm:focus,
    .form-select-sm:focus {
        border-color: #4a90e2;
        /* Subtle professional blue */
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.15);
        outline: none;
    }

    .form-check {
        margin-bottom: 0.75rem;
    }

    .tooltip-inner {
        max-width: 220px;
        font-size: 0.8rem;
    }

    .file-upload-info {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .readonly-field {
        background-color: #f5f5f5 !important;
        cursor: not-allowed;
    }

    /* Subtle highlight for inputs */
    .highlight-field {
        background-color: #fafafa;
    }

    .required-field {
        border-left: 3px solid #d9534f !important;
        /* Muted red, not too bright */
        padding-left: 6px;
    }

    /* Section Headings */
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        /* Match form label text color */
        margin-bottom: 0.75rem;
        padding-bottom: 0.25rem;
        border-bottom: 1px solid #eaeaea;
    }


    .section-title i {
        color: #333;
        /* single accent color */
    }

    /* Card Style */
    .card {
        border: 1px solid #eaeaea;
        border-radius: 8px;
    }

    .card-body {
        background-color: #fff;
    }

    /* Buttons - Professional Style */
    .btn-primary {
        background-color: #4a90e2;
        border-color: #4a90e2;
        font-size: 0.9rem;
        padding: 0.45rem 1rem;
        border-radius: 6px;
    }

    .btn-primary:hover {
        background-color: #3b78c2;
        border-color: #3b78c2;
    }

    /* Alerts - muted style */
    .alert {
        border-radius: 6px;
        font-size: 0.9rem;
        padding: 0.6rem 0.9rem;
    }

    /* Compact Mobile Layout */
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
    <h1 class="mb-3 fs-4">Register Theatre Booking</h1>

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
            <form action="{{ route('booked_theatre.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="bookingForm">
                @csrf
                <input type="hidden" name="id" value="{{ old('id', $surgery->SessionNumber ?? '') }}">

                <div class="col-lg-4 col-md-6 col-12">
                    <label for="patient_number" class="form-label small">Patient Number</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="patient_number" id="patient_number"
                            class="form-control form-control-sm highlight-field"
                            value="{{ old('patient_number', $surgery->PatientNumber ?? '') }}">
                        <button type="button" id="searchPatient" class="btn btn-primary btn-sm">Search</button>
                    </div>
                </div>

                <!-- Patient Information (Non-Collapsible) -->
                <div class="form-section mb-2"> <!-- Using layout's .form-section for professional borders/icons -->
                    <h5 class="section-title" style="color: #333;">
                        <!-- <i class="fas fa-user me-2 text-primary"></i> -->
                        Theatre Booking Details - Patient Information
                    </h5>
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="session_number" class="form-label small">Session Number</label>
                            <input type="text" name="session_number" id="session_number" class="form-control form-control-sm highlight-field {{ $surgery ? 'readonly-field required-field' : '' }}" value="{{ old('session_number', $surgery->SessionNumber ?? '') }}" {{ $surgery ? 'readonly' : '' }}>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="full_name" class="form-label small required">Full Name <span class="asterisk" title="Required">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control form-control-sm highlight-field required-field {{ $surgery ? 'readonly-field' : '' }}" value="{{ old('full_name', $surgery->PatientName ?? '') }}" {{ $surgery ? 'readonly' : '' }} required aria-required="true">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="patient_number" class="form-label small">Patient Number</label>
                            <input type="text" name="patient_number" id="patient_number" class="form-control form-control-sm highlight-field {{ $surgery ? 'readonly-field' : '' }}" value="{{ old('patient_number', $surgery->PatientNumber ?? '') }}" {{ $surgery ? 'readonly' : '' }}>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="phone_numbers" class="form-label small" data-bs-toggle="tooltip" title="Enter patient's contact numbers">Phone Numbers</label>
                            <input type="text" name="phone_numbers" id="phone_numbers" class="form-control form-control-sm highlight-field" value="{{ old('phone_numbers', $surgery->phone_numbers ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="age" class="form-label small required">Age <span class="asterisk" title="Required">*</span></label>
                            <input type="number" name="age" id="age" class="form-control form-control-sm highlight-field required-field" value="{{ old('age', $surgery->Age ?? $surgery->age ?? '') }}" min="0" required aria-required="true">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="surgery_type" class="form-label small">Surgery Type</label>
                            <select name="surgery_type" id="surgery_type" class="form-select form-select-sm highlight-field">
                                <option value="">Select Type</option>
                                @php
                                $selectedType = old('surgery_type', $surgery->surgery_type ?? $surgery->SessionType ?? '');
                                @endphp
                                @foreach (['Elective','Urgent','Emergency', 'Minor', 'Major'] as $type)
                                <option value="{{ $type }}" {{ $selectedType === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="theatre_request_date" class="form-label small">Theatre Request Date</label>
                            <input type="date" name="theatre_request_date" id="theatre_request_date" class="form-control form-control-sm highlight-field {{ $surgery ? 'readonly-field' : '' }}" value="{{ old('theatre_request_date', $surgery->Requested_on ?? '') }}" {{ $surgery ? 'readonly' : '' }}>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="scheduling_status" class="form-label small" data-bs-toggle="tooltip" title="Current scheduling status of the surgery">Scheduling Status</label>
                            <select name="scheduling_status" id="scheduling_status" class="form-select form-select-sm highlight-field">
                                <option value="">Select Status</option>
                                @foreach (['Need Surgery', 'SHA Submitted; Pending Approval', 'Insurance Approved/Deposit Paid; Ready to Schedule', 'Scheduled', 'Completed', 'Inactive', 'SHA Rejected', 'Cancelled'] as $status)
                                <option value="{{ $status }}" {{ old('scheduling_status', $surgery->scheduling_status ?? 'Need Surgery') == $status ? 'selected' : '' }}>{{ $status }}</option>
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
                            <input type="text" name="diagnosis" id="diagnosis" class="form-control form-control-sm highlight-field" value="{{ old('diagnosis', $surgery->diagnosis ?? $surgery->PreferredName ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="surgery" class="form-label small required">Surgery <span class="asterisk" title="Required">*</span></label>
                            <input type="text" name="surgery" id="surgery" class="form-control form-control-sm highlight-field required-field" value="{{ old('surgery', $surgery->surgery ?? $surgery->theatre_procedure_requested ?? '') }}" required aria-required="true">
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="surgery_category" class="form-label small">Surgery Category</label>
                            <input type="text" name="surgery_category" id="surgery_category" class="form-control form-control-sm highlight-field" value="{{ old('surgery_category', $surgery->surgery_category ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="icd10_code" class="form-label small" data-bs-toggle="tooltip" title="International Classification of Diseases code">ICD10 Code</label>
                            <input type="text" name="icd10_code" id="icd10_code" class="form-control form-control-sm highlight-field" value="{{ old('icd10_code', $surgery->icd10_code ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="surgeon" class="form-label small required">Surgeon <span class="asterisk" title="Required">*</span></label>
                            <input type="text" name="surgeon" id="surgeon" class="form-control form-control-sm highlight-field required-field" value="{{ old('surgeon', $surgery->surgeon ?? $surgery->Consultant ?? '') }}" required aria-required="true">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="second_surgeon" class="form-label small">Second Surgeon</label>
                            <input type="text" name="second_surgeon" id="second_surgeon" class="form-control form-control-sm highlight-field" value="{{ old('second_surgeon', $surgery->second_surgeon ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="proposed_date_of_surgery" class="form-label small">Proposed Date of Surgery</label>
                            <input type="date" name="proposed_date_of_surgery" id="proposed_date_of_surgery" class="form-control form-control-sm highlight-field" value="{{ old('proposed_date_of_surgery', $surgery->proposed_date_of_surgery ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="date_of_surgery" class="form-label small">Date of Surgery</label>
                            <input type="date" name="date_of_surgery" id="date_of_surgery" class="form-control form-control-sm highlight-field" value="{{ old('date_of_surgery', $surgery->date_of_surgery ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="theatre_room" class="form-label small">Theatre Room</label>
                            <select name="theatre_room" id="theatre_room" class="form-select form-select-sm highlight-field">
                                <option value="">Select Room</option>
                                @foreach (['Room1', 'Room2', 'Room3', 'Room4','OPERATION ROOM 1','OPERATION ROOM 2','OPERATION ROOM 3','OPERATION ROOM 4','OPERATION ROOM 5','OPERATION ROOM 6','Other'] as $room)
                                <option value="{{ $room }}" {{ old('theatre_room', $surgery->theatre_room ?? $surgery->OperationRoom ?? '') == $room ? 'selected' : '' }}>{{ $room }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="case_order" class="form-label small" data-bs-toggle="tooltip" title="Order of the case in the theatre schedule">Case Order</label>
                            <input type="text" name="case_order" id="case_order" class="form-control form-control-sm highlight-field" value="{{ old('case_order', $surgery->case_order ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="length_of_surgery" class="form-label small">Length of Surgery</label>
                            <input type="text" name="length_of_surgery" id="length_of_surgery" class="form-control form-control-sm highlight-field" value="{{ old('length_of_surgery', $surgery->length_of_surgery ?? '') }}" placeholder="e.g., 2 hours">
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="special_needs" class="form-label small">Special Needs</label>
                            <input type="text" name="special_needs" id="special_needs" class="form-control form-control-sm highlight-field" value="{{ old('special_needs', $surgery->special_needs ?? '') }}" placeholder="e.g., Wheelchair access">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 form-check">
                            <label for="requires_anesthesia_clearance" class="form-label small">Requires Anesthesia Clearance</label>
                            <input type="checkbox" name="requires_anesthesia_clearance" id="requires_anesthesia_clearance" class="form-check-input highlight-field" {{ old('requires_anesthesia_clearance', $surgery->requires_anesthesia_clearance ?? false) ? 'checked' : '' }} value="1">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="anesthesia_clearance_notes" class="form-label small">Anesthesia Clearance Notes</label>
                            <textarea name="anesthesia_clearance_notes" id="anesthesia_clearance_notes" class="form-control form-control-sm highlight-field" rows="2">{{ old('anesthesia_clearance_notes', $surgery->anesthesia_clearance_notes ?? '') }}</textarea>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="cpt_code" class="form-label small">CPT Code</label>
                            <input type="text" name="cpt_code" id="cpt_code" class="form-control form-control-sm highlight-field" value="{{ old('cpt_code', $surgery->cpt_code ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="notes_comments" class="form-label small">Notes/Comments</label>
                            <textarea name="notes_comments" id="notes_comments" class="form-control form-control-sm highlight-field" rows="2">{{ old('notes_comments', $surgery->notes_comments ?? '') }}</textarea>
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
                            <select name="payment_type" id="payment_type" class="form-select form-select-sm highlight-field">
                                <option value="">Select Payment Type</option>
                                @foreach (['Cash', 'Other Insurance', 'Compassionate', 'SHA + Other', 'SHA'] as $payment)
                                <option value="{{ $payment }}" {{ old('payment_type', $surgery->payment_type ?? '') == $payment ? 'selected' : '' }}>{{ $payment }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="sha_procedure" class="form-label small" data-bs-toggle="tooltip" title="Procedure code for SHA insurance">SHA Procedure</label>
                            <input type="text" name="sha_procedure" id="sha_procedure" class="form-control form-control-sm highlight-field" value="{{ old('sha_procedure', $surgery->sha_procedure ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="sha_code" class="form-label small">SHA Code</label>
                            <input type="text" name="sha_code" id="sha_code" class="form-control form-control-sm highlight-field" value="{{ old('sha_code', $surgery->sha_code ?? '') }}">
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="sha_approved_amount" class="form-label small">SHA Approved Amount</label>
                            <input type="number" name="sha_approved_amount" id="sha_approved_amount" class="form-control form-control-sm highlight-field" value="{{ old('sha_approved_amount', $surgery->sha_approved_amount ?? '') }}" step="0.01" min="0">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="sha_expiry_date" class="form-label small">SHA Expiry Date</label>
                            <input type="date" name="sha_expiry_date" id="sha_expiry_date" class="form-control form-control-sm highlight-field" value="{{ old('sha_expiry_date', $surgery->sha_expiry_date ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="secondary_payer" class="form-label small">Secondary Payer</label>
                            <select name="secondary_payer" id="secondary_payer" class="form-select form-select-sm highlight-field">
                                <option value="">Select Payer</option>
                                @foreach (['AAP Insurance', 'Britam Insurance', 'First Assurance Insurance', 'GA Insurance', 'Jubilee Insurance', 'None'] as $payer)
                                <option value="{{ $payer }}" {{ old('secondary_payer', $surgery->secondary_payer ?? '') == $payer ? 'selected' : '' }}>{{ $payer }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="second_payer_approved_amount" class="form-label small">Second Payer Approved Amount</label>
                            <input type="number" name="second_payer_approved_amount" id="second_payer_approved_amount" class="form-control form-control-sm highlight-field" value="{{ old('second_payer_approved_amount', $surgery->second_payer_approved_amount ?? '') }}" step="0.01" min="0">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="date_deposit_paid" class="form-label small">Date Deposit Paid</label>
                            <input type="date" name="date_deposit_paid" id="date_deposit_paid" class="form-control form-control-sm highlight-field" value="{{ old('date_deposit_paid', $surgery->date_deposit_paid ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="deposit_amount" class="form-label small">Deposit Amount</label>
                            <input type="number" name="deposit_amount" id="deposit_amount" class="form-control form-control-sm highlight-field" value="{{ old('deposit_amount', $surgery->deposit_amount ?? '') }}" step="0.01" min="0">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 form-check">
                            <label for="sha_eligible" class="form-label small" data-bs-toggle="tooltip" title="Check if eligible for SHA coverage">SHA Eligible</label>
                            <input type="checkbox" name="sha_eligible" id="sha_eligible" class="form-check-input highlight-field" {{ old('sha_eligible', $surgery->sha_eligible ?? false) ? 'checked' : '' }} value="1">
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
                            <select name="department" id="department" class="form-select form-select-sm highlight-field">
                                <option value="">Select Department</option>
                                @foreach (['General Surgery', 'Orthopedics', 'Neurosurgery', 'Cardiology', 'Other'] as $dept)
                                <option value="{{ $dept }}" {{ old('department', $surgery->department ?? '') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="post_op_location" class="form-label small">Post-Op Location</label>
                            <select name="post_op_location" id="post_op_location" class="form-select form-select-sm highlight-field">
                                <option value="">Select Location</option>
                                @foreach (['Ward', 'ICU', 'Recovery Room', 'Outpatient'] as $location)
                                <option value="{{ $location }}" {{ old('post_op_location', $surgery->post_op_location ?? '') == $location ? 'selected' : '' }}>{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="department_additional" class="form-label small">Additional Department</label>
                            <select name="department_additional" id="department_additional" class="form-select form-select-sm highlight-field">
                                <option value="">Select Additional Dept</option>
                                @foreach (['None', 'Radiology', 'Pathology', 'Anesthesiology'] as $dept)
                                <option value="{{ $dept }}" {{ old('department_additional', $surgery->department_additional ?? '') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="appointment_id" class="form-label small">Appointment ID</label>
                            <input type="text" name="appointment_id" id="appointment_id" class="form-control form-control-sm highlight-field" value="{{ old('appointment_id', $surgery->appointment_id ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="entry_date" class="form-label small">Entry Date</label>
                            <input type="date" name="entry_date" id="entry_date" class="form-control form-control-sm highlight-field" value="{{ old('entry_date', $surgery->entry_date ?? '') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="attachments" class="form-label small" data-bs-toggle="tooltip" title="Upload relevant documents (PDF, JPG, PNG)">Attachments</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-control form-control-sm highlight-field" multiple>
                            <div class="file-upload-info mt-1">No files selected</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" name="action" value="save" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    });

$(document).ready(function () {
    $("#searchPatient").on("click", function () {
        let patientNumber = $("#patient_number").val().trim();

        if (patientNumber !== "") {
            $.ajax({
                url: "{{ url('patient-info') }}/" + patientNumber,
                method: "GET",
                success: function (response) {
                    if (response.success) {
                        $("#full_name").val(response.data.PatientName);
                        $("#age").val(response.data.Age);
                        $("#gender").val(response.data.Gender);
                        $("#phone_numbers").val(response.data.PatientCellPhone);
                        $("#county").val(response.data.County);
                    }
                },
                error: function () {
                    alert("Patient not found.");
                    $("#full_name, #age, #gender, #phone_numbers, #county").val("");
                }
            });
        } else {
            alert("Please enter a Patient Number first.");
        }
    });
});
</script>
@endsection