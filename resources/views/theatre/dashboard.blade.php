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

  <!-- Stats Cards Row - Minimized Widgets -->
  <div class="row">
    @php
    $statusConfig = [
    'Need Surgery' => ['icon' => 'fa-exclamation-triangle', 'class' => 'status-need-surgery'],
    'SHA Submitted; Pending Approval' => ['icon' => 'fa-clock', 'class' => 'status-sha-pending'],
    'Insurance Approved/Deposit Paid; Ready to Schedule' => ['icon' => 'fa-check-circle', 'class' => 'status-ready-schedule'],
    'Scheduled' => ['icon' => 'fa-calendar-check', 'class' => 'status-scheduled'],
    'Completed' => ['icon' => 'fa-check', 'class' => 'status-completed'],
    'Inactive' => ['icon' => 'fa-pause', 'class' => 'status-inactive'],
    'SHA Rejected' => ['icon' => 'fa-times-circle', 'class' => 'status-sha-rejected'],
    'Cancelled' => ['icon' => 'fa-ban', 'class' => 'status-cancelled'],
    'Finalized' => ['icon' => 'fa-star', 'class' => 'status-finalized'],
    ];

    // Role-based statuses
    if (auth()->user()->isNurse()) {
    $statuses = [
    'Need Surgery',
    'SHA Submitted; Pending Approval',
    'Insurance Approved/Deposit Paid; Ready to Schedule',
    'Inactive',
    'SHA Rejected',
    'Cancelled',
    ];
    } else {
    $statuses = array_keys($statusConfig); // all statuses
    }
    @endphp

    @foreach ($statuses as $status)
    @php $config = $statusConfig[$status] ?? ['icon' => 'fa-users', 'class' => 'border-left-primary']; @endphp
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="card stat-widget {{ $config['class'] }} shadow h-100">
        <div class="card-body p-0 d-flex flex-column h-100">
          <div class="d-flex align-items-center flex-grow-1">
            <div class="stat-icon">
              <i class="fas {{ $config['icon'] }}"></i>
            </div>
            <div class="flex-grow-1">
              <h5 class="card-title mb-1">{{ $counts[$status] ?? 0 }}</h5>
              <p class="card-text status-text mb-0">{{ $status }}</p>
            </div>
            <a href="{{ route('surgeries.filter', ['status' => $status]) }}"
              class="details-link align-self-start ms-auto me-2">Details</a>
          </div>
        </div>
        <div class="card-footer bg-transparent p-2 border-0">
          <small class="footer-text">Last 30 days</small>
        </div>
      </div>
    </div>
    @endforeach
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
            data: [{
                {
                  $counts['Need Surgery'] ?? 0
                }
              },
              {
                {
                  $counts['SHA Submitted; Pending Approval'] ?? 0
                }
              },
              {
                {
                  $counts['Insurance Approved/Deposit Paid; Ready to Schedule'] ?? 0
                }
              },
              {
                {
                  $counts['Scheduled'] ?? 0
                }
              },
              {
                {
                  $counts['Completed'] ?? 0
                }
              },
              {
                {
                  $counts['Inactive'] ?? 0
                }
              },
              {
                {
                  $counts['SHA Rejected'] ?? 0
                }
              },
              {
                {
                  $counts['Cancelled'] ?? 0
                }
              },
              {
                {
                  $counts['Finalized'] ?? 0
                }
              }
            ],
            backgroundColor: [
              '#f59e0b', '#eab308', '#10b981', '#159ed5', '#059669', '#6b7280', '#ef4444', '#dc2626', '#8b5cf6'
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