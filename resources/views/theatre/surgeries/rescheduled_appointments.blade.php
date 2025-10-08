@extends('layout.app')

@section('title', 'Rescheduled Appointments')

@section('content')
<style>
    /* Retained/enhanced table styles for very wide table */
    .table-responsive {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        overflow-x: auto;
        border: none;
    }
    .table {
        min-width: 1800px;
    }
    .table-sm th,
    .table-sm td {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }
    .table-sm th {
        background: #343a40;
        color: #ffffff;
        font-weight: 600;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }
    .table-hover tbody tr:hover {
        background-color: #e6eef5;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100vw;
        }
        .btn, .mb-3 {
            display: none !important;
        }
    }
    /* Mobile compactness */
    @media (max-width: 576px) {
        .table-sm th, .table-sm td { font-size: 0.75rem; padding: 0.25rem 0.375rem; }
    }
</style>

<div class="container-fluid">
  <!-- Page Header (matches dashboard) -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Rescheduled Appointments</h1>
      <p class="mb-0 text-muted">View history of rescheduled surgery bookings</p>
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
    </div>
  </div>

  <!-- Filter Form (added compact form for consistency; optional) -->
  <form method="GET" action="{{ route('surgeries.rescheduled') }}" class="card shadow-sm mb-4">
    <div class="card-body p-3">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label for="query" class="form-label small">Search</label>
          <input type="text" name="query" id="query" class="form-control form-control-sm highlight-field" placeholder="Search by patient name, session, or reason" value="{{ $query ?? '' }}">
        </div>
        <div class="col-md-3">
          <label for="start_date" class="form-label small">Start Date</label>
          <input type="date" name="start_date" id="start_date" class="form-control form-control-sm highlight-field" value="{{ $start_date ?? '' }}">
        </div>
        <div class="col-md-3">
          <label for="end_date" class="form-label small">End Date</label>
          <input type="date" name="end_date" id="end_date" class="form-control form-control-sm highlight-field" value="{{ $end_date ?? '' }}">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
        </div>
      </div>
    </div>
  </form>

  <!-- Rescheduled Table (matches dashboard table structure) -->
  <div class="row">
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Reschedule History</h6>
          <div class="d-flex gap-2">
            <a href="{{ route('surgeries.rescheduled.export_csv') }}" class="btn btn-success btn-sm">
              <i class="fas fa-file-csv me-1"></i> Export CSV
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
              <i class="fas fa-print me-1"></i> Print
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm table-hover mb-0">
              <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                <tr>
                  <th>S.No</th>
                  <th>Patient Name</th>
                  <th>Session Number</th>
                  <th>Patient Number</th>
                  <th>Prev Date of Surgery</th>
                  <th>Prev Surgery</th>
                  <th>Prev Surgeon</th>
                  <th>Prev Surgery Type</th>
                  <th>Prev Surgery Category</th>
                  <th>Prev SHA Procedure</th>
                  <th>Prev Case Order</th>
                  <th>Prev Theatre Room</th>
                  <th>To</th>
                  <th>New Date of Surgery</th>
                  <th>New Surgery</th>
                  <th>New Surgeon</th>
                  <th>New Surgery Type</th>
                  <th>New Surgery Category</th>
                  <th>New SHA Procedure</th>
                  <th>New Case Order</th>
                  <th>New Theatre Room</th>
                  <th>Reason</th>
                  <th>Rescheduled By</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                @forelse($reschedules as $reschedule)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reschedule->surgery->full_name ?? '-' }}</td>
                    <td>{{ $reschedule->surgery->session_number ?? '-' }}</td>
                    <td>{{ $reschedule->surgery->patient_number ?? '-' }}</td>
                    <td>{{ $reschedule->previous_date_of_surgery ? $reschedule->previous_date_of_surgery->format('Y-m-d') : '-' }}</td>
                    <td>{{ $reschedule->previous_surgery ?? '-' }}</td>
                    <td>{{ $reschedule->previous_surgeon ?? '-' }}</td>
                    <td>{{ $reschedule->previous_surgery_type ?? '-' }}</td>
                    <td>{{ $reschedule->previous_surgery_category ?? '-' }}</td>
                    <td>{{ $reschedule->previous_sha_procedure ?? '-' }}</td>
                    <td>{{ $reschedule->previous_case_order ?? '-' }}</td>
                    <td>{{ $reschedule->previous_theatre_room ?? '-' }}</td>
                    <td>&rarr;</td>
                    <td>{{ $reschedule->surgery->date_of_surgery ? $reschedule->surgery->date_of_surgery->format('Y-m-d') : '-' }}</td>
                    <td>{{ $reschedule->surgery->surgery ?? '-' }}</td>
                    <td>{{ $reschedule->surgery->surgeon ?? '-' }}</td>
                    <td>{{ $reschedule->surgery->surgery_type ?? '-' }}</td>
                    <td>{{ $reschedule->surgery->surgery_category ?? '-' }}</td>
                    <td>{{ $reschedule->surgery->sha_procedure ?? '-' }}</td>
                    <td>{{ $reschedule->surgery->case_order ?? '-' }}</td>
                    <td>{{ $reschedule->surgery->theatre_room ?? '-' }}</td>
                    <td>{{ $reschedule->reason ?? '-' }}</td>
                    <td>{{ $reschedule->user->name ?? '-' }}</td>
                    <td>{{ $reschedule->created_at->format('Y-m-d H:i') }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="24" class="text-center text-muted">No rescheduled appointments found.</td>
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