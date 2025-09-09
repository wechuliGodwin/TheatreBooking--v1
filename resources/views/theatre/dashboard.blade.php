@extends('layout.app')

@section('title', 'Dashboard - Analytics')

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
      <p class="mb-0 text-muted">Welcome back! Here's what's happening with your project today.</p>
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
  <div class="row mb-4">
    <!-- Users Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card stat-card border-left-primary h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="stat-icon users-icon text-white">
              <i class="fas fa-users"></i>
            </div>
            <div class="flex-grow-1">
              <h5 class="card-title mb-0">26K</h5>
              <p class="card-text mb-1">Users</p>
              <small class="text-danger"><i class="fas fa-arrow-down me-1"></i>12.4%</small>
            </div>
          </div>
        </div>
        <div class="card-footer bg-transparent">
          <small class="text-muted">Compared to last month</small>
        </div>
      </div>
    </div>

    <!-- Income Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card stat-card border-left-success h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="stat-icon income-icon text-white">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="flex-grow-1">
              <h5 class="card-title mb-0">$6,200</h5>
              <p class="card-text mb-1">Income</p>
              <small class="text-success"><i class="fas fa-arrow-up me-1"></i>40.9%</small>
            </div>
          </div>
        </div>
        <div class="card-footer bg-transparent">
          <small class="text-muted">Compared to last month</small>
        </div>
      </div>
    </div>

    <!-- Conversion Rate Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card stat-card border-left-warning h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="stat-icon conversion-icon text-white">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="flex-grow-1">
              <h5 class="card-title mb-0">2.49%</h5>
              <p class="card-text mb-1">Conversion Rate</p>
              <small class="text-success"><i class="fas fa-arrow-up me-1"></i>84.7%</small>
            </div>
          </div>
        </div>
        <div class="card-footer bg-transparent">
          <small class="text-muted">Compared to last month</small>
        </div>
      </div>
    </div>

    <!-- Sessions Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card stat-card border-left-danger h-100">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="stat-icon sessions-icon text-white">
              <i class="fas fa-clock"></i>
            </div>
            <div class="flex-grow-1">
              <h5 class="card-title mb-0">44K</h5>
              <p class="card-text mb-1">Sessions</p>
              <small class="text-danger"><i class="fas fa-arrow-down me-1"></i>23.6%</small>
            </div>
          </div>
        </div>
        <div class="card-footer bg-transparent">
          <small class="text-muted">Compared to last month</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row mb-4">
    <!-- Traffic Chart -->
    <div class="col-xl-8 col-lg-7 mb-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Traffic</h6>
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

    <!-- Metrics Cards -->
    <div class="col-xl-4 col-lg-5 mb-4">
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body text-center">
              <h5 class="card-title">89k</h5>
              <p class="card-text text-muted mb-0">friends</p>
              <div class="progress mt-2">
                <div class="progress-bar" style="width: 75%"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body text-center">
              <h5 class="card-title">459</h5>
              <p class="card-text text-muted mb-0">feeds</p>
              <div class="progress mt-2">
                <div class="progress-bar bg-success" style="width: 60%"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body text-center">
              <h5 class="card-title">973k</h5>
              <p class="card-text text-muted mb-0">followers</p>
              <div class="progress mt-2">
                <div class="progress-bar bg-warning" style="width: 85%"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body text-center">
              <h5 class="card-title">1,792</h5>
              <p class="card-text text-muted mb-0">tweets</p>
              <div class="progress mt-2">
                <div class="progress-bar bg-info" style="width: 40%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Traffic & Sales Row -->
  <div class="row mb-4">
    <!-- Weekly Stats -->
    <div class="col-xl-8 col-lg-7 mb-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Traffic & Sales</h6>
          <div class="btn-group btn-group-sm" role="group">
            <button class="btn btn-outline-secondary active">New Clients</button>
            <button class="btn btn-outline-secondary">Recurring</button>
          </div>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-md-2 mb-3">
              <h6 class="mb-1">9,123</h6>
              <p class="text-muted mb-0 small">New Clients</p>
            </div>
            <div class="col-md-2 mb-3">
              <h6 class="mb-1">22,643</h6>
              <p class="text-muted mb-0 small">Recurring Clients</p>
            </div>
            <div class="col-md-8">
              <canvas id="weeklyChart" height="80"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Demographics -->
    <div class="col-xl-4 col-lg-5 mb-4">
      <div class="card">
        <div class="card-header">
          <h6 class="mb-0">Demographics</h6>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span>Male</span>
            <span class="badge bg-primary">43%</span>
          </div>
          <div class="progress mb-3">
            <div class="progress-bar bg-primary" style="width: 43%"></div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <span>Female</span>
            <span class="badge bg-success">37%</span>
          </div>
          <div class="progress mb-4">
            <div class="progress-bar bg-success" style="width: 37%"></div>
          </div>

          <h6 class="mb-2">Traffic Sources</h6>
          <div class="d-flex justify-content-between small mb-1">
            <span>Organic Search</span>
            <span class="text-muted">56%</span>
          </div>
          <div class="progress mb-2" style="height: 6px;">
            <div class="progress-bar bg-info" style="width: 56%"></div>
          </div>
          <div class="d-flex justify-content-between small mb-1">
            <span>Facebook</span>
            <span class="text-muted">15%</span>
          </div>
          <div class="progress mb-2" style="height: 6px;">
            <div class="progress-bar bg-primary" style="width: 15%"></div>
          </div>
          <div class="d-flex justify-content-between small mb-1">
            <span>Twitter</span>
            <span class="text-muted">11%</span>
          </div>
          <div class="progress mb-2" style="height: 6px;">
            <div class="progress-bar bg-warning" style="width: 11%"></div>
          </div>
          <div class="d-flex justify-content-between small">
            <span>LinkedIn</span>
            <span class="text-muted">8%</span>
          </div>
          <div class="progress" style="height: 6px;">
            <div class="progress-bar bg-success" style="width: 8%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Users Table -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Recent Users</h6>
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
                  <th>User</th>
                  <th>Country</th>
                  <th>Usage</th>
                  <th>Payment Method</th>
                  <th>Activity</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="https://via.placeholder.com/40x40" class="rounded-circle me-3"
                        alt="User" width="40" height="40">
                      <div>
                        <div class="fw-bold">Yiorgos Avraamu</div>
                        <small class="text-muted">New | Registered: Jan 1, 2025</small>
                      </div>
                    </div>
                  </td>
                  <td>🇺🇸 USA</td>
                  <td>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar" style="width: 50%"></div>
                    </div>
                    <small class="text-muted">50%</small>
                    <br><small class="text-muted">Jun 11, 2025 - Jul 10, 2025</small>
                  </td>
                  <td>
                    <span class="badge bg-primary">Credit Card</span>
                  </td>
                  <td>
                    <small class="text-success">Last login 10 sec ago</small>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-primary btn-action">Info</button>
                      <button class="btn btn-sm btn-outline-secondary btn-action">Edit</button>
                      <button class="btn btn-sm btn-outline-danger btn-action">Delete</button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="https://via.placeholder.com/40x40" class="rounded-circle me-3"
                        alt="User" width="40" height="40">
                      <div>
                        <div class="fw-bold">Avram Tarasios</div>
                        <small class="text-muted">Recurring | Registered: Jan 1, 2025</small>
                      </div>
                    </div>
                  </td>
                  <td>🇬🇷 Greece</td>
                  <td>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar bg-success" style="width: 10%"></div>
                    </div>
                    <small class="text-success">10%</small>
                    <br><small class="text-muted">Jun 11, 2025 - Jul 10, 2025</small>
                  </td>
                  <td>
                    <span class="badge bg-success">PayPal</span>
                  </td>
                  <td>
                    <small class="text-primary">Last login 5 minutes ago</small>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-primary btn-action">Info</button>
                      <button class="btn btn-sm btn-outline-secondary btn-action">Edit</button>
                      <button class="btn btn-sm btn-outline-danger btn-action">Delete</button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="https://via.placeholder.com/40x40" class="rounded-circle me-3"
                        alt="User" width="40" height="40">
                      <div>
                        <div class="fw-bold">Quintin Ed</div>
                        <small class="text-muted">New | Registered: Jan 1, 2025</small>
                      </div>
                    </div>
                  </td>
                  <td>🇬🇧 UK</td>
                  <td>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar bg-warning" style="width: 74%"></div>
                    </div>
                    <small class="text-warning">74%</small>
                    <br><small class="text-muted">Jun 11, 2025 - Jul 10, 2025</small>
                  </td>
                  <td>
                    <span class="badge bg-info">Bank Transfer</span>
                  </td>
                  <td>
                    <small class="text-info">Last login 1 hour ago</small>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-primary btn-action">Info</button>
                      <button class="btn btn-sm btn-outline-secondary btn-action">Edit</button>
                      <button class="btn btn-sm btn-outline-danger btn-action">Delete</button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="https://via.placeholder.com/40x40" class="rounded-circle me-3"
                        alt="User" width="40" height="40">
                      <div>
                        <div class="fw-bold">Enéas Kwadwo</div>
                        <small class="text-muted">New | Registered: Jan 1, 2025</small>
                      </div>
                    </div>
                  </td>
                  <td>🇳🇬 Nigeria</td>
                  <td>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar bg-danger" style="width: 98%"></div>
                    </div>
                    <small class="text-danger">98%</small>
                    <br><small class="text-muted">Jun 11, 2025 - Jul 10, 2025</small>
                  </td>
                  <td>
                    <span class="badge bg-warning">Crypto</span>
                  </td>
                  <td>
                    <small class="text-muted">Last login last month</small>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <button class="btn btn-sm btn-outline-primary btn-action">Info</button>
                      <button class="btn btn-sm btn-outline-secondary btn-action">Edit</button>
                      <button class="btn btn-sm btn-outline-danger btn-action">Delete</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js Initialization -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Traffic Chart
    const trafficCtx = document.getElementById('trafficChart').getContext('2d');
    new Chart(trafficCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
          label: 'Traffic',
          data: [65000, 72000, 68000, 75000, 71000, 78000, 82000],
          borderColor: '#3498db',
          backgroundColor: 'rgba(52, 152, 219, 0.1)',
          tension: 0.4,
          fill: true
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
                return value / 1000 + 'k';
              }
            }
          }
        }
      }
    });

    // Weekly Chart
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
      type: 'bar',
      data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
          label: 'New Clients',
          data: [1200, 1900, 1500, 2200, 1800, 900, 600],
          backgroundColor: '#3498db'
        }, {
          label: 'Recurring',
          data: [800, 1100, 950, 1300, 1050, 700, 450],
          backgroundColor: '#2ecc71'
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
                return value / 100 + 'k';
              }
            }
          }
        }
      }
    });
  });
</script>
@endsection