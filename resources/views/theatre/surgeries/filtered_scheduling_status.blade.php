@extends('layout.app')

@section('title', 'Surgery List')

@section('content')
<style>
    .session-emergency { background-color: #ffcccc; }
    .session-urgent { background-color: #ffccff; }
    .session-elective { background-color: #ccffcc; }
    .status-filter { max-width: 200px; }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $status ? $status : 'All Surgeries' }} Appointments</h5>
                    {{-- Show "Create New" button only if status is "Need Surgery" --}}
                    @if ($status == 'Need Surgery')
                        <a href="{{ route('surgery.create') }}" class="btn btn-success me-2">Create New</a>
                    @endif
                    <form class="d-flex" method="GET" action="{{ route('surgeries.filter') }}">
                        <select name="status" class="form-select me-2 status-filter">
                            <option value="">All Statuses</option>
                            @foreach (['Need Surgery', 'SHA Submitted; Pending Approval', 'Insurance Approved/Deposit Paid; Ready to Schedule', 'Scheduled', 'Completed', 'Inactive', 'SHA Rejected'] as $statusOption)
                                <option value="{{ $statusOption }}" {{ $status == $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
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

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Session Number</th>
                                <th>Patient Name</th>
                                <th>Patient Number</th>
                                <th>Age</th>
                                <th>Surgery</th>
                                <th>Surgery Type</th>
                                <th>Consultant</th>
                                <th>Theatre Room</th>
                                <th>Scheduling Status</th>
                                <th> </th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surgeries as $surgery)
                                <tr class="{{ $surgery->surgery_type === 'Emergency' ? 'session-emergency' : ($surgery->surgery_type === 'Urgent' ? 'session-urgent' : 'session-elective') }}">
                                    <td>{{ $surgery->session_number ?? 'N/A' }}</td>
                                    <td>{{ $surgery->full_name ?? $surgery->PatientName ?? 'N/A' }}</td>
                                    <td>{{ $surgery->patient_number ?? $surgery->PatientNumber ?? 'N/A' }}</td>
                                    <td>{{ $surgery->age ?? $surgery->Age ?? 'N/A' }}</td>
                                    <td>{{ $surgery->surgery ?? $surgery->theatre_procedure_requested ?? 'N/A' }}</td>
                                    <td>{{ $surgery->surgery_type ?? $surgery->SessionType ?? 'N/A' }}</td>
                                    <td>{{ $surgery->surgeon ?? $surgery->Consultant ?? 'N/A' }}</td>
                                    <td>{{ $surgery->theatre_room ?? $surgery->OperationRoom ?? 'N/A' }}</td>
                                    <td>{{ $surgery->scheduling_status ?? $surgery->Status ?? 'N/A' }}</td>
                                    <td>{{ $surgery->created_at ? $surgery->created_at->format('Y-m-d') : ($surgery->booking_date ?? 'N/A') }}</td>
                                    <td>
                                        @if (($surgery->scheduling_status ?? $surgery->Status ?? '') === 'Need Surgery')
                                            @if ($surgery->id)
                                                <a href="{{ route('surgery.book', urlencode($surgery->id)) }}" class="btn btn-primary btn-sm me-2">Book</a>
                                            @elseif ($surgery->session_number)
                                                <a href="{{ route('surgery.book', urlencode($surgery->session_number)) }}" class="btn btn-primary btn-sm me-2">Book</a>
                                            @else
                                                <span class="text-muted">Book N/A</span>
                                            @endif
                                        @else
                                            @if ($surgery->id)
                                                <a href="{{ route('surgery.edit', urlencode($surgery->id)) }}" class="btn btn-warning btn-sm me-2">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                
                                            @elseif ($surgery->session_number)
                                                <a href="{{ route('surgery.edit', urlencode($surgery->session_number)) }}" class="btn btn-warning btn-sm me-2">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                            @else
                                                <span class="text-muted">Edit N/A</span>
                                            @endif
                                        @endif

                                         @if($surgery->id)
                                            <form action="{{ route('surgeries.destroy', $surgery->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                        @else
                                                <span class="text-muted">Remote</span>
                                        @endif

                                        @if (($surgery->scheduling_status ?? $surgery->Status ?? '') !== 'Cancelled')
                                            <button class="btn btn-outline-danger btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#cancelModal" 
                                                    data-identifier="{{ $surgery->session_number ?? $surgery->id }}"
                                                    data-is-remote="{{ $surgery->id ? 'false' : 'true' }}"
                                                    data-patient-name="{{ $surgery->full_name ?? $surgery->PatientName ?? 'N/A' }}">
                                                <i class="bi bi-x-circle"></i> Cancel
                                            </button>
                                        @endif
                                        
                                    </td>
                                </tr>
                            @endforeach
                            @if ($surgeries->isEmpty())
                                <tr>
                                    <td colspan="11" class="text-center">No surgeries found for the selected filter.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Surgery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel the surgery for <strong id="patientName"></strong> (Session: <span id="sessionNumber"></span>)?</p>
                <form id="cancelForm" method="POST" action="{{ route('surgery.cancel') }}">
                    @csrf
                    <input type="hidden" name="identifier" id="cancelIdentifier">
                    <input type="hidden" name="is_remote" id="cancelIsRemote">
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3" required placeholder="Enter the reason for cancellation..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cancelled_at" class="form-label">Cancelled At</label>
                        <input type="date" name="cancelled_at" id="cancelled_at" class="form-control" required value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                    <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var cancelModal = document.getElementById('cancelModal');
    var cancelForm = document.getElementById('cancelForm');
    var cancellationReason = document.getElementById('cancellation_reason');
    var cancelledAt = document.getElementById('cancelled_at');

    // When modal is shown, fill in patient/session info
    cancelModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var identifier = button.getAttribute('data-identifier');
        var isRemote = button.getAttribute('data-is-remote');
        var patientName = button.getAttribute('data-patient-name');

        document.getElementById('cancelIdentifier').value = identifier;
        document.getElementById('cancelIsRemote').value = isRemote;
        document.getElementById('patientName').textContent = patientName;
        document.getElementById('sessionNumber').textContent = identifier;

        // Reset form fields
        cancellationReason.value = '';
        cancelledAt.value = '{{ now()->format('Y-m-d') }}';
    });

    // When modal is hidden, clear the form
    cancelModal.addEventListener('hidden.bs.modal', function () {
        cancelForm.reset();
        document.getElementById('patientName').textContent = '';
        document.getElementById('sessionNumber').textContent = '';
    });

    // AJAX form submission
    if (cancelForm) {
        cancelForm.addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(cancelForm);

            fetch(cancelForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide modal
                    var modalInstance = bootstrap.Modal.getInstance(cancelModal);
                    modalInstance.hide();

                    // Clear form fields
                    cancelForm.reset();
                    document.getElementById('patientName').textContent = '';
                    document.getElementById('sessionNumber').textContent = '';

                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: Failed to cancel surgery.');
                console.error('Error:', error);
            });
        });
    }
});
</script>
@endsection