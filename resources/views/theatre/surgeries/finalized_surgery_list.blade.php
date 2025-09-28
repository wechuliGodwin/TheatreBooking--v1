@extends('layout.app')

@section('title', 'Finalized Surgeries')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Finalized Surgeries</h5>
                    <div>
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">Back to Dashboard</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Date Filter Form -->
                    <form method="GET" action="{{ route('surgeries.finalized') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="query" class="form-label">Search</label>
                                <input type="text" name="query" id="query" class="form-control form-control-sm" value="{{ $query ?? '' }}" placeholder="Search by patient number, diagnosis, or surgeon">
                            </div>
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ $start_date }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ $end_date }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Surgeries Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Session Number</th>
                                    <th>Patient Name</th>
                                    <th>Patient Number</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Diagnosis</th>
                                    <th>Procedure</th>
                                    <th>Surgery Type</th>
                                    <th>Surgeon</th>
                                    <th>Theatre Room</th>
                                    <th>Booking Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($filteredFinalizedSurgeries as $surgery)
                                    <tr>
                                        <td>{{ $surgery->SessionNumber }}</td>
                                        <td>{{ $surgery->PatientName }}</td>
                                        <td>{{ $surgery->PatientNumber }}</td>
                                        <td>{{ $surgery->Age }}</td>
                                        <td>{{ $surgery->Gender }}</td>
                                        <td>{{ $surgery->PreferredName }}</td>
                                        <td>{{ $surgery->theatre_procedure_requested }}</td>
                                        <td>{{ $surgery->SessionType }}</td>
                                        <td>{{ $surgery->Consultant }}</td>
                                        <td>{{ $surgery->OperationRoom }}</td>
                                        <td>{{ \Carbon\Carbon::parse($surgery->booking_date)->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $surgery->Status }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('surgery_details.show', urlencode($surgery->SessionNumber)) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">No finalized surgeries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection