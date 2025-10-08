@extends('layout.app')

@section('title', 'Surgery Details')

@section('content')
<div class="container-fluid">
    <!-- Surgery Details Card -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Surgery Details for {{ $surgery->PreferredName }}</h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('surgery.book', urlencode($surgery->SessionNumber)) }}" class="btn btn-sm btn-outline-primary btn-action">Book</a>
                        <a href="#" class="btn btn-sm btn-outline-danger btn-action">Cancel</a>
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary btn-action">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="{{ asset('img/avator/user.png') }}" class="rounded-circle mb-3" alt="Patient Avatar" width="100" height="100">
                            <h6 class="fw-bold">Pt. Name: {{ $surgery->PatientName }}</h6>
                            <h6 class="fw-bold">Surgery: {{ $surgery->PreferredName }}</h6>
                            <small class="text-muted">Requested On: {{ $surgery->Requested_on ? \Carbon\Carbon::parse($surgery->Requested_on)->format('M d, Y') : 'N/A' }}</small>
                        </div>
                        <div class="col-md-9">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 fw-bold">Session Number:</dt>
                                <dd class="col-sm-8">{{ $surgery->SessionNumber ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Booking Date:</dt>
                                <dd class="col-sm-8">{{ $surgery->booking_date ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Patient Number:</dt>
                                <dd class="col-sm-8">{{ $surgery->PatientNumber ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Age:</dt>
                                <dd class="col-sm-8">{{ $surgery->Age ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Gender:</dt>
                                <dd class="col-sm-8">{{ $surgery->Gender ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Procedure Requested:</dt>
                                <dd class="col-sm-8">{{ $surgery->theatre_procedure_requested ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Session Type:</dt>
                                <dd class="col-sm-8">{{ $surgery->SessionType ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Consultant:</dt>
                                <dd class="col-sm-8">{{ $surgery->Consultant ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Operation Room:</dt>
                                <dd class="col-sm-8">{{ $surgery->OperationRoom ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Status:</dt>
                                <dd class="col-sm-8">{{ $surgery->Status ?? 'N/A' }}</dd>

                                <dt class="col-sm-4 fw-bold">Theatre Day Case:</dt>
                                <dd class="col-sm-8">{{ $surgery->TheatreDayCase ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection