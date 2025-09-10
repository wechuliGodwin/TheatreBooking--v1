@extends('layout.app')

@section('title', 'Dashboard - Analytics')

@section('content')
<div class="container-fluid">

    <!-- Surgeries Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Requested Surgeries</h6>
                    <div>
                        <select class="form-select form-select-sm d-inline-block w-auto" style="width: 120px;">
                            <option>All</option>
                            <option>New</option>
                            <option>Recurring</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Patient Number</th>
                                    <th>Appointment Number</th>
                                    <th>Phone</th>
                                    <th>Specialization</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surgeries as $surgery)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/40x40" class="rounded-circle me-3" alt="User" width="40" height="40">
                                            <div>
                                                <div class="fw-bold">{{ $surgery->full_name }}</div>
                                                <small class="text-muted">Registered: {{ $surgery->created_at ? \Carbon\Carbon::parse($surgery->created_at)->format('M d, Y') : 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $surgery->patient_number }}</td>
                                    <td>{{ $surgery->appointment_number }}</td>
                                    <td>{{ $surgery->phone }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $surgery->specialization }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-primary btn-action">View</a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary btn-action">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection