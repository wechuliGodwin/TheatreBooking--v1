
@extends('layout.app')

@section('title', 'Dashboard - Analytics')

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
      <p class="mb-0 text-muted">Welcome back!</p>
      @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    </div>
    <div class="d-flex align-items-center">
      <select class="form-select form-select-sm me-2" style="width: auto;">
        <option>January - July 2025</option>
        <option>February - August 2025</option>
        <option>March - September 2025</option>
      </select>
      <button class="btn btn-outline-primary btn-sm">
        <i class="fas fa-download me-1"></i> Export
      </button>
    </div>
  </div>

  <!-- Stats Cards Row -->
  <div class="row">
        @foreach (['Need Surgery', 'SHA Submitted; Pending Approval', 'Insurance Approved/Deposit Paid; Ready to Schedule', 'Scheduled', 'Completed', 'Inactive', 'SHA Rejected', 'Cancelled', 'Finalized'] as $status)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon users-icon text-white">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">{{ $counts[$status] }}</h5>
                                <p class="card-text mb-1">{{ $status }}</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('surgeries.filter', ['status' => $status]) }}" class="small card-text">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <small class="text-muted">Last 30 days</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row mb-4">
    <!-- Traffic Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Surgery Status Distribution</h6>
          <div>
            <button class="btn btn-sm btn-outline-secondary me-2">Day</button>
            <button class="btn btn-sm btn-outline-secondary me-2">Month</button>
            <button class="btn btn-sm btn-primary">Year</button>
          </div>
        </div>
        <div class="card-body">
          <canvas id="trafficChart" height="100"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Users Table -->
  <div class="row">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Surgeries</h6>
        </div>
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
                        <th>Surgeon</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
              <tbody>
                    @foreach ($recentSurgeries as $surgery)
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
                                @if ($surgery->session_number ?? $surgery->SessionNumber)
                                    <a href="{{ route('surgery.book', urlencode($surgery->session_number ?? $surgery->SessionNumber)) }}">Edit</a>
                                    <a href="{{ route('surgery_details.show', urlencode($surgery->session_number ?? $surgery->SessionNumber)) }}" class="ms-2">Details</a>
                                @else
                                    <span class="text-muted">Edit N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if ($recentSurgeries->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center">No recent surgeries found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

  <!-- Chart.js Initialization -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Surgery Status Distribution Chart
      const trafficCtx = document.getElementById('trafficChart').getContext('2d');
      new Chart(trafficCtx, {
        type: 'bar',
        data: {
          labels: ['Need Surgery', 'SHA Submitted', 'Pending Approval', 'Ready to Schedule', 'Scheduled', 'Completed', 'Inactive', 'SHA Rejected', 'Cancelled', 'Finalized'],
          datasets: [{
            label: 'Surgery Count',
            data: [
              {{ $counts['Need Surgery'] }},
              {{ $counts['SHA Submitted; Pending Approval'] }},
              {{ $counts['Insurance Approved/Deposit Paid; Ready to Schedule'] }},
              {{ $counts['Scheduled'] }},
              {{ $counts['Completed'] }},
              {{ $counts['Inactive'] }},
              {{ $counts['SHA Rejected'] }},
              {{ $counts['Cancelled'] }},
              {{ $counts['Finalized'] }}
            ],
            backgroundColor: [
              '#3498db', '#2ecc71', '#f39c12', '#1abc9c', '#2980b9', '#27ae60', '#e74c3c', '#f1c40f', '#c0392b', '#8e44ad'
            ]
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return value;
                }
              }
            }
          }
        }
      });
    });
  </script>
@endsection