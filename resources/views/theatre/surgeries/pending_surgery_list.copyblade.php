@extends('layout.app')

@section('title', 'Requested Surgeries')

@section('content')
<style>
    .session-emergency {
        background-color: #ffcccc;
    }

    .session-urgent {
        background-color: #ffccff;
    }

    .session-elective {
        background-color: #ccffcc;
    }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Booking Surgeries</h5>
                    <a href="{{ route('surgery.create') }}" class="btn btn-success me-2">Create New</a>
                    <form class="d-flex" method="GET" action="{{ route('requested_surgeries.index') }}">
                        <input type="text" name="query" class="form-control me-2" placeholder="Search by Patient Number, Preferred Name, or Consultant" value="{{ $query ?? '' }}">
                        <input type="date" name="start_date" class="form-control me-2" value="{{ $start_date }}">
                        <input type="date" name="end_date" class="form-control me-2" value="{{ $end_date }}">
                        <button type="submit" class="btn btn-primary">Search/Filter</button>
                    </form>
                </div>
                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Session Number</th>
                                <th>Booking Date</th>
                                <th>Requested On</th>
                                <th>Patient Name</th>
                                <th>Patient Number</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Procedure Requested</th>
                                <th>Session Type</th>
                                <th>Preferred Name</th>
                                <th>Consultant</th>
                                <th>Operation Room</th>
                                <th>Status</th>
                                <th>Billing Status</th>
                                <th>Theatre Day Case</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookingSurgeries as $surgery)
                                <tr class="{{ $surgery->SessionType === 'Emergency' ? 'session-emergency' : ($surgery->SessionType === 'Urgent' ? 'session-urgent' : 'session-elective') }}">
                                    <td>{{ $surgery->SessionNumber }}</td>
                                    <td>{{ $surgery->booking_date }}</td>
                                    <td>{{ $surgery->Requested_on }}</td>
                                    <td>{{ $surgery->PatientName }}</td>
                                    <td>{{ $surgery->PatientNumber }}</td>
                                    <td>{{ $surgery->Age }}</td>
                                    <td>{{ $surgery->Gender }}</td>
                                    <td>{{ $surgery->theatre_procedure_requested }}</td>
                                    <td>{{ $surgery->SessionType }}</td>
                                    <td>{{ $surgery->PreferredName }}</td>
                                    <td>{{ $surgery->Consultant }}</td>
                                    <td>{{ $surgery->OperationRoom }}</td>
                                    <td>{{ $surgery->Status }}</td>
                                    <td>{{ $surgery->BillingApproved ?? 'NULL' }}</td>
                                    <td>{{ $surgery->TheatreDayCase }}</td>
                                    <td>
                                        <a href="{{ route('surgery.book', urlencode($surgery->SessionNumber)) }}">Book</a>
                                    </td>
                                </tr>
                            @endforeach
                            @if (empty($bookingSurgeries))
                                <tr>
                                    <td colspan="16" class="text-center">No surgeries found for the selected filter.</td>
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