@extends('layout.app')

@section('title', 'Finalized Surgeries')

@section('content')
<div class="container-fluid">
  <!-- Page Header (matches dashboard structure) -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Finalized Surgeries</h1>
      <p class="mb-0 text-muted">View completed and archived surgery records</p>
      @if (session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
      @endif
      @if (session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
      @endif
    </div>
    <div class="d-flex align-items-center">
      <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm me-2">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
      </a>
      <!-- Export button for consistency -->
      <button class="btn btn-outline-primary btn-sm">
        <i class="fas fa-download me-1"></i> Export
      </button>
    </div>
  </div>

  <!-- Filter Form (integrated compactly like dashboard controls) -->
  <form method="GET" action="{{ route('surgeries.finalized') }}" class="card shadow-sm mb-4">
    <div class="card-body p-3">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label for="query" class="form-label small">Search</label>
          <input type="text" name="query" id="query" class="form-control form-control-sm highlight-field" value="{{ $query ?? '' }}" placeholder="Search by patient number, diagnosis, or surgeon">
        </div>
        <div class="col-md-3">
          <label for="start_date" class="form-label small">Start Date</label>
          <input type="date" name="start_date" id="start_date" class="form-control form-control-sm highlight-field" value="{{ $start_date }}">
        </div>
        <div class="col-md-3">
          <label for="end_date" class="form-label small">End Date</label>
          <input type="date" name="end_date" id="end_date" class="form-control form-control-sm highlight-field" value="{{ $end_date }}">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
        </div>
      </div>
    </div>
  </form>

  <!-- Finalized Surgeries Table (matches dashboard's table structure) -->
  <div class="row">
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Finalized Surgery Records</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive"> {{-- Enhanced for mobile scrolling --}}
            <table class="table table-striped table-hover">
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
                      <span class="badge bg-success status-badge">{{ $surgery->Status }}</span> {{-- Using layout's status-badge for consistency --}}
                    </td>
                    <td>
                      <a href="{{ route('surgery_details.show', urlencode($surgery->SessionNumber)) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i> View
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="13" class="text-center text-muted">No finalized surgeries found matching your criteria.</td>
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