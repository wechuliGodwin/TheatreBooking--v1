@extends('layout.app')

@section('title', 'Cancelled Surgeries')

@section('content')
<style>
    .session-emergency { background-color: #ffcccc; }
    .session-urgent { background-color: #ffccff; }
    .session-elective { background-color: #ccffcc; }
    .status-filter { max-width: 200px; }
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
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Cancelled Surgeries</h5>
                    <form class="d-flex" method="GET" action="{{ route('surgeries.cancelled') }}">
                        <select name="cancellation_type" class="form-select me-2 status-filter">
                            <option value="">All Cancellation Types</option>
                            @foreach (['pre_cancelled', 'post_cancelled'] as $type)
                                <option value="{{ $type }}" {{ $cancellation_type == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="query" class="form-control me-2" placeholder="Search by Session Number, Name, or Surgeon" value="{{ $query ?? '' }}">
                        <input type="date" name="start_date" class="form-control me-2" value="{{ $start_date }}">
                        <input type="date" name="end_date" class="form-control me-2" value="{{ $end_date }}">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card-body table-responsive">
                    <div class="mb-3 d-flex justify-content-end gap-2">
                        <a href="{{ route('surgeries.cancelled.export_csv', [
                            'cancellation_type' => $cancellation_type,
                            'query' => $query,
                            'start_date' => $start_date,
                            'end_date' => $end_date
                        ]) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </a>
                        <button onclick="window.print()" class="btn btn-primary btn-sm">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                    <table class="table table-striped table-bordered table-sm table-hover mb-0">
                        <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>Session Number</th>
                                <th>Patient Name</th>
                                <th>Patient Number</th>
                                <th>Age</th>
                                <th>Surgery</th>
                                <th>Surgery Type</th>
                                <th>Consultant</th>
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
                                        <a href="{{ route('surgery.edit', $surgery->id) }}" class="btn btn-warning btn-sm me-2">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($surgeries->isEmpty())
                                <tr>
                                    <td colspan="12" class="text-center">No cancelled surgeries found for the selected filter.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection