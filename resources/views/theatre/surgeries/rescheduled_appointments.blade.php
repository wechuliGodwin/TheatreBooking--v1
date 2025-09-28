@extends('layout.app')

@section('title', 'Rescheduled Appointments')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Rescheduled Appointments</h5>
                </div>
                <div class="card-body table-responsive">
                    <div class="mb-3 d-flex justify-content-end gap-2">
                        <a href="{{ route('surgeries.rescheduled.export_csv') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </a>
                        <button onclick="window.print()" class="btn btn-primary btn-sm">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
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
                                    <td>=&gt;</td>
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
                                    <td colspan="24" class="text-center">No rescheduled appointments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
</style>
@endsection