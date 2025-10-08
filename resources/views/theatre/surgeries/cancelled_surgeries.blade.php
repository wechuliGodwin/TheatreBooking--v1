@extends('layout.app')

@section('title', 'Cancelled Surgeries')

@section('content')
<style>
    .session-emergency { background-color: #ffcccc; }
    .session-urgent { background-color: #ffccff; }
    .session-elective { background-color: #ccffcc; }
    .status-filter { max-width: 200px; }
    
    /* Retained/enhanced table styles for wide/responsive table */
    .table-responsive {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        overflow-x: auto;
        border: none;
    }
    .table {
        min-width: 1200px;
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
      <h1 class="h3 mb-0 text-gray-800">Cancelled Surgeries</h1>
      <p class="mb-0 text-muted">View and filter cancelled surgery records</p>
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

  <!-- Filter Form (compact, integrated like dashboard) -->
  <form method="GET" action="{{ route('surgeries.cancelled') }}" class="card shadow-sm mb-4">
    <div class="card-body p-3">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label for="cancellation_type" class="form-label small">Cancellation Type</label>
          <select name="cancellation_type" id="cancellation_type" class="form-select form-select-sm highlight-field status-filter">
            <option value="">All Cancellation Types</option>
            @foreach (['pre_cancelled', 'post_cancelled'] as $type)
              <option value="{{ $type }}" {{ $cancellation_type == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label for="query" class="form-label small">Search</label>
          <input type="text" name="query" id="query" class="form-control form-control-sm highlight-field" placeholder="Search by Session Number, Name, or Surgeon" value="{{ $query ?? '' }}">
        </div>
        <div class="col-md-2">
          <label for="start_date" class="form-label small">Start Date</label>
          <input type="date" name="start_date" id="start_date" class="form-control form-control-sm highlight-field" value="{{ $start_date }}">
        </div>
        <div class="col-md-2">
          <label for="end_date" class="form-label small">End Date</label>
          <input type="date" name="end_date" id="end_date" class="form-control form-control-sm highlight-field" value="{{ $end_date }}">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
        </div>
      </div>
    </div>
  </form>

  <!-- Cancelled Surgeries Table (matches dashboard table structure) -->
  <div class="row">
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Cancelled Surgery Records</h6>
          <div class="d-flex gap-2">
            <a href="{{ route('surgeries.cancelled.export_csv', [
              'cancellation_type' => $cancellation_type,
              'query' => $query,
              'start_date' => $start_date,
              'end_date' => $end_date
            ]) }}" class="btn btn-success btn-sm">
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
                  <th>Session Number</th>
                  <th>Patient Name</th>
                  <th>Patient Number</th>
                  <th>Age</th>
                  <th>Surgery</th>
                  <th>Surgery Type</th>
                  <th>Surgeon</th>
                  <th>Theatre Room</th>
                  <th>Cancellation Type</th>
                  <th>Cancellation Reason</th>
                  <th>Cancelled At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($surgeries as $surgery)
                  <tr class="{{ $surgery->surgery_type === 'Emergency' ? 'session-emergency' : ($surgery->surgery_type === 'Urgent' ? 'session-urgent' : 'session-elective') }}">
                    <td>{{ $surgery->session_number ?? 'N/A' }}</td>
                    <td>{{ $surgery->full_name ?? 'N/A' }}</td>
                    <td>{{ $surgery->patient_number ?? 'N/A' }}</td>
                    <td>{{ $surgery->age ?? 'N/A' }}</td>
                    <td>{{ $surgery->surgery ?? 'N/A' }}</td>
                    <td>{{ $surgery->surgery_type ?? 'N/A' }}</td>
                    <td>{{ $surgery->surgeon ?? 'N/A' }}</td>
                    <td>{{ $surgery->theatre_room ?? 'N/A' }}</td>
                    <td>{{ $surgery->cancellation_type ?? 'N/A' }}</td>
                    <td>{{ $surgery->cancellation_reason ?? 'N/A' }}</td>
                    <td>{{ $surgery->cancelled_at ? $surgery->cancelled_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                      <a href="{{ route('surgery.edit', $surgery->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-pencil-alt me-1"></i> Edit
                      </a>
                    </td>
                  </tr>
                @endforeach
                @if ($surgeries->isEmpty())
                  <tr>
                    <td colspan="12" class="text-center text-muted">No cancelled surgeries found for the selected filter.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection