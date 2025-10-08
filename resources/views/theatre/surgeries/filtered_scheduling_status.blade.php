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
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">{{ $status ? $status : 'All Surgeries' }} Appointments</h1>
      <p class="mb-0 text-muted">Manage and filter surgery bookings</p>
      @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
    </div>
    <div class="d-flex align-items-center">
      {{-- Show "Create New" button only if status is "Need Surgery" --}}
      @if ($status == 'Need Surgery')
        <a href="{{ route('surgery.create') }}" class="btn btn-success me-2">Create New</a>
      @endif
      {{-- Integrated Filter Controls (compact like dashboard) --}}
      <form class="d-flex align-items-center" method="GET" action="{{ route('surgeries.filter') }}" style="gap: 0.5rem;">
        <select name="status" class="form-select form-select-sm status-filter" style="width: auto;">
          <option value="">All Statuses</option>
          @foreach (['Need Surgery', 'SHA Submitted; Pending Approval', 'Insurance Approved/Deposit Paid; Ready to Schedule', 'Scheduled', 'Completed', 'Inactive', 'SHA Rejected'] as $statusOption)
            <option value="{{ $statusOption }}" {{ $status == $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
          @endforeach
        </select>
        <input type="text" name="query" class="form-control form-control-sm" placeholder="Search..." value="{{ $query ?? '' }}" style="width: 150px;">
        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $start_date }}" style="width: 120px;">
        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $end_date }}" style="width: 120px;">
        <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
      </form>
    </div>
  </div>
<!-- Surgery List Table (matches dashboard's recent surgeries structure) -->
  <div class="row">
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Surgery List</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive"> {{-- Added for mobile scrolling --}}
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Session Number</th>
                  <th>Patient Name</th>
                  <th>Patient Number</th>
                  <th>Age</th>
                  <th>Surgery</th>
                  <th>Surgery Type</th>
                  <th>Surgeon</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($surgeries as $surgery)
                  <tr class="{{ ($surgery->surgery_type ?? $surgery->SessionType ?? '') === 'Emergency' ? 'session-emergency' : (($surgery->surgery_type ?? $surgery->SessionType ?? '') === 'Urgent' ? 'session-urgent' : 'session-elective') }}">
                    <td>{{ $surgery->session_number ?? $surgery->SessionNumber ?? 'N/A' }}</td>
                    <td>{{ $surgery->full_name ?? $surgery->PatientName ?? 'N/A' }}</td>
                    <td>{{ $surgery->patient_number ?? $surgery->PatientNumber ?? 'N/A' }}</td>
                    <td>{{ $surgery->age ?? $surgery->Age ?? 'N/A' }}</td>
                    <td>{{ $surgery->surgery ?? $surgery->theatre_procedure_requested ?? 'N/A' }}</td>
                    <td>{{ $surgery->surgery_type ?? $surgery->SessionType ?? 'N/A' }}</td>
                    <td>{{ $surgery->surgeon ?? $surgery->Consultant ?? 'N/A' }}</td>
                    <td>{{ $surgery->scheduling_status ?? $surgery->Status ?? 'N/A' }}</td>
                    <td>
                      @if (($surgery->scheduling_status ?? $surgery->Status ?? '') === 'Need Surgery')
                        @if ($surgery->id ?? $surgery->session_number)
                          <a href="{{ route('surgery.book', urlencode($surgery->id ?? $surgery->session_number)) }}" class="btn btn-primary btn-sm me-1">Book</a>
                        @else
                          <span class="text-muted">Book N/A</span>
                        @endif
                      @else
                        @if ($surgery->id ?? $surgery->session_number)
                          <a href="{{ route('surgery.edit', urlencode($surgery->id ?? $surgery->session_number)) }}" class="btn btn-warning btn-sm me-1">Edit</a>
                        @else
                          <span class="text-muted">Edit N/A</span>
                        @endif
                      @endif

                      @if ($surgery->id ?? $surgery->session_number)
                        <a href="{{ route('surgery_details.show', urlencode($surgery->id ?? $surgery->session_number)) }}" class="btn btn-info btn-sm me-1">Details</a>
                      @endif

                      @if($surgery->id)
                        <form action="{{ route('surgeries.destroy', $surgery->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this record?');" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm me-1">Delete</button>
                        </form>
                      @else
                        
                      @endif

                      @if (($surgery->scheduling_status ?? $surgery->Status ?? '') !== 'Cancelled')
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal" 
                                data-identifier="{{ $surgery->session_number ?? $surgery->id }}"
                                data-is-remote="{{ $surgery->id ? 'false' : 'true' }}"
                                data-patient-name="{{ $surgery->full_name ?? $surgery->PatientName ?? 'N/A' }}">
                          <i class="fas fa-times"></i> Cancel
                        </button>
                      @endif
                    </td>
                  </tr>
                @endforeach
                @if ($surgeries->isEmpty())
                  <tr>
                    <td colspan="9" class="text-center">No surgeries found for the selected filter.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Cancellation Modal (retained and styled consistently) -->
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