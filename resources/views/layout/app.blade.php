<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CoreUI CSS (if available) or custom styles -->
    <link href="https://unpkg.com/@coreui/coreui@4.2.0/dist/css/coreui.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            position: fixed;
            width: 260px;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.25rem 0;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #3498db;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .navbar-brand {
            font-weight: 600;
            color: #2c3e50 !important;
        }

        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .theme-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.25rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card .card-body {
            position: relative;
            z-index: 2;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(52, 152, 219, 0.05));
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
        }

        .users-icon {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .income-icon {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .conversion-icon {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }

        .sessions-icon {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .trend-up {
            color: #27ae60;
        }

        .trend-down {
            color: #e74c3c;
        }

        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.625rem;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .footer {
            background-color: #ffffff;
            border-top: 1px solid #dee2e6;
            padding: 1rem 0;
            margin-top: auto;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-toggler {
                border: none;
                padding: 0.5rem;
            }
        }

        .progress {
            height: 0.5rem;
            background-color: #e9ecef;
            border-radius: 0.25rem;
        }

        .progress-bar {
            background: linear-gradient(90deg, #3498db, #2980b9);
        }

        .nav-group-toggle {
            display: flex;
            align-items: center;
        }

        .nav-group-toggle::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.2s;
        }

        .nav-group-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .nav-group-items {
            padding-left: 1.5rem;
        }

        .nav-group-items .nav-link {
            font-size: 0.875rem;
        }

        .nav-icon-bullet {
            display: inline-block;
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background-color: #bdc3c7;
            margin-right: 0.5rem;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3">
        <div class="sidebar-header d-flex align-items-center mb-4 pb-3 border-bottom">
            <h4 class="text-white mb-0">Theatre Booking</h4>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link active" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </nav>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('requested_surgeries.index', 'Booking') }}">
                <i class="fas fa-chart-bar"></i>
                <span>Need Surgery</span>
            </a>
        </nav>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('requested_surgeries.index', 'Operation') }}">
                <i class="fas fa-chart-bar"></i>
                <span>Operation</span>
            </a>
        </nav>

        <nav class="nav flex-column">
            <a class="nav-link" href="#">
                <i class="fas fa-chart-bar"></i>
                <span>Urgent Surgery</span>
            </a>
        </nav>
        <nav class="nav flex-column">
            <a class="nav-link" href="#">
                <i class="fas fa-chart-bar"></i>
                <span>Elective Surgery</span>
            </a>
        </nav>

        <!-- <nav class="nav flex-column">
            <a class="nav-link nav-group-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#theatreStatusSubmenu" aria-expanded="false">
                <i class="fas fa-chart-bar"></i>
                <span>Theatre Status</span>
            </a>
            <ul class="nav-group-items collapse" id="theatreStatusSubmenu">

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        SHA Submitted, Pending Approval
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        Ready to Schedule
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        Scheduled
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        Rescheduled
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        Completed
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        SHA Rejected
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        Inactive
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        Cancelled
                    </a>
                </li>
            </ul> -->
        <a class="nav-link" href="#">
            <i class="fas fa-users"></i>
            <span>Finalized Surgeries</span>
        </a>
        <!-- <nav class="nav flex-column">
                <a class="nav-link nav-group-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#pinkFormSubmenu" aria-expanded="false">
                    <i class="fas fa-chart-bar"></i>
                    <span>Pink Form</span>
                </a>
                <ul class="nav-group-items collapse" id="pinkFormSubmenu">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="nav-icon-bullet"></span>
                            Create
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="nav-icon-bullet"></span>
                            Pending List
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="nav-icon-bullet"></span>
                            Approved List
                        </a>
                    </li>
                </ul>
            </nav> -->
        </nav>
        <nav class="nav flex-column">
            <a class="nav-link nav-group-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#reportsSubmenu" aria-expanded="false">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <ul class="nav-group-items collapse" id="reportsSubmenu">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        Summary
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon-bullet"></span>
                        Detailed
                    </a>
                </li>
            </ul>
        </nav>
        <nav class="nav flex-column">
            <a class="nav-link nav-group-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#accountsSubmenu" aria-expanded="false">
                <i class="fas fa-chart-bar"></i>
                <span>Accounts</span>
            </a>
            <ul class="nav-group-items collapse" id="accountsSubmenu">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
            <hr class="text-muted my-4">
        </nav>
        <div class="mt-auto">
            <a class="nav-link text-danger" href="#">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content d-flex flex-column flex-grow-1">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a class="navbar-brand" href="#">Admin Dashboard</a>

                <div class="navbar-nav ms-auto align-items-center">
                    <!-- Theme Toggle -->
                    <div class="theme-toggle me-3">
                        <i class="fas fa-moon" id="themeToggle"></i>
                    </div>

                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <a class="dropdown-toggle d-flex align-items-center text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('img/avator/user.png') }}" class="rounded-circle me-2" alt="User" width="32" height="32">
                            <span class="d-none d-md-inline">Admin User</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer mt-auto py-4">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="mb-0 text-muted">&copy; 2025 Theatre Dashboard. Powered by Wechuli.</p>
                    </div>
                    <div class="col-auto">
                        <p class="mb-0 text-muted">Version 1.0</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom JS -->
    <script>
        // Theme toggle functionality
        document.getElementById('themeToggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-theme');
            this.classList.toggle('fa-moon');
            this.classList.toggle('fa-sun');
        });

        // Sidebar toggle for mobile
        const sidebar = document.querySelector('.sidebar');
        const navbarToggler = document.querySelector('.navbar-toggler');

        navbarToggler.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        // Initialize charts (if needed)
        document.addEventListener('DOMContentLoaded', function() {
            // Any chart initialization can go here
        });
    </script>
</body>

</html>