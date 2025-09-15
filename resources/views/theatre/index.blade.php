@extends('layout.app')

@section('content')
<h1>Theatre Bookings</h1>

@if(isset($error))
<div class="alert alert-danger">{{ $error }}</div>
@else
@if(count($results) > 0)
<table class="table table-striped">
    <thead>
        <tr>
            <th>Session Number</th>
            <th>Booking Date</th>
            <th>Requested On</th>
            <th>Patient Number</th>
            <th>Gender</th>
            <th>Session Type</th>
            <th>Operation Room</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $row)
        <tr>
            <td>{{ $row->SessionNumber }}</td>
            <td>{{ $row->BookingDate }}</td>
            <td>{{ $row->RequestedOn }}</td>
            <td>{{ $row->PatientNumber }}</td>
            <td>{{ $row->Gender ?? 'N/A' }}</td>
            <td>{{ $row->SessionType ?? 'N/A' }}</td>
            <td>{{ $row->OperationRoom ?? 'N/A' }}</td>
            <td>{{ $row->Status ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No data found for the specified date range.</p>
@endif
@endif
@endsection