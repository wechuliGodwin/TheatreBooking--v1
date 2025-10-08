<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="...">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #159ed5;
            --primary-dark: #0f7dad;
            --primary-light: #1eb8f5;
            --sidebar-bg: #0f7dad;
            /* Altered to a darker shade matching #159ed5 for a professional blue sidebar theme */
            --sidebar-hover: #159ed5;
            /* Hover uses the exact #159ed5 for accent matching */
            --text-muted: #8b92a7;
            --border-color: #e5e7eb;
            --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            --required-color: #ef4444;
            /* New: Red for mandatory asterisk */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
            color: #1f2937;
            overflow-x: hidden;
        }

        /* Sidebar Styles (updated for brighter menu text to match header) */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background-color: var(--sidebar-bg);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h4 {
            color: #fff;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-header h4 i {
            color: var(--primary-light);
            font-size: 1.5rem;
        }

        .sidebar-nav {
            padding: 1rem 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #e5e7eb;
            /* Brighter default text to match header's white (less grey) */
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            font-size: 0.9375rem;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
            transform: translateX(2px);
        }

        .nav-link.active {
            background-color: var(--primary-light);
            color: #fff;
            box-shadow: 0 4px 12px rgba(21, 158, 213, 0.3);
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.875rem;
            font-size: 1.125rem;
            text-align: center;
        }

        .nav-group-toggle {
            cursor: pointer;
            position: relative;
        }

        .nav-group-toggle::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1rem;
            font-size: 0.75rem;
            color: #e5e7eb;
            /* Match brighter text */
            transition: transform 0.2s ease;
        }

        .nav-group-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .nav-group-items {
            padding-left: 0;
            margin-top: 0.25rem;
        }

        .nav-group-items .nav-link {
            padding-left: 3rem;
            font-size: 0.875rem;
            color: #d1d5db;
            /* Lighter grey for sub-items (less dark, hierarchical but visible) */
        }

        .nav-group-items .nav-link::before {
            content: '';
            width: 6px;
            height: 6px;
            background-color: #d1d5db;
            /* Lighter bullet to match sub-text */
            border-radius: 50%;
            position: absolute;
            left: 2.25rem;
        }

        .nav-group-items .nav-link:hover::before,
        .nav-group-items .nav-link.active::before {
            background-color: var(--primary-light);
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 1rem 0.75rem;
        }

        .logout-btn {
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.9375rem;
            font-weight: 500;
        }

        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
        }

        .logout-btn i {
            width: 20px;
            margin-right: 0.875rem;
            font-size: 1.125rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* Navbar (unchanged) */
        .navbar {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-brand {
            font-weight: 600;
            color: #1f2937;
            font-size: 1.125rem;
        }

        .theme-toggle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--text-muted);
            background-color: #f3f4f6;
        }

        .theme-toggle:hover {
            background-color: #e5e7eb;
            color: var(--primary-color);
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: #d8e7fdff;
        }

        .user-dropdown:hover {
            background-color: #f3f4f6;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
            object-fit: cover;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.625rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 0.625rem;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: var(--border-color);
        }

        /* Main Content Area */
        main {
            flex: 1;
            padding: 2rem 1.5rem;
        }

        /* Cards (unchanged) */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            background-color: #fff;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Footer (unchanged) */
        .footer {
            background-color: #fff;
            border-top: 1px solid var(--border-color);
            padding: 1.5rem;
            margin-top: auto;
        }

        .footer-text {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin: 0;
        }

        /* Mobile Menu Toggle (unchanged) */
        .mobile-menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            background-color: #f3f4f6;
            border: none;
            color: #1f2937;
        }

        .mobile-menu-toggle:hover {
            background-color: #e5e7eb;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
        }

        /* Dark Theme (extended for new form styles below; adjusted sidebar text for better visibility) */
        body.dark-theme {
            background-color: #0f1218;
            color: #e5e7eb;
        }

        body.dark-theme .navbar {
            background-color: #1a1d29;
            border-bottom-color: #2d3343;
        }

        body.dark-theme .navbar-brand {
            color: #fff;
        }

        body.dark-theme .theme-toggle {
            background-color: #252938;
            color: #a0aec0;
        }

        body.dark-theme .theme-toggle:hover {
            background-color: #2d3343;
            color: var(--primary-color);
        }

        body.dark-theme .user-dropdown {
            color: #e5e7eb;
        }

        body.dark-theme .user-dropdown:hover {
            background-color: #252938;
        }

        body.dark-theme .mobile-menu-toggle {
            background-color: #252938;
            color: #e5e7eb;
        }

        body.dark-theme .mobile-menu-toggle:hover {
            background-color: #2d3343;
        }

        body.dark-theme .card {
            background-color: #1a1d29;
            color: #e5e7eb;
        }

        body.dark-theme .card-header {
            border-bottom-color: #2d3343;
        }

        body.dark-theme .footer {
            background-color: #1a1d29;
            border-top-color: #2d3343;
        }

        body.dark-theme .dropdown-menu {
            background-color: #1a1d29;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        body.dark-theme .dropdown-item:hover {
            background-color: #252938;
        }

        /* Dark theme sidebar adjustments (brighter text for consistency) */
        body.dark-theme .nav-link {
            color: #f1f5f9;
            /* Even brighter in dark mode */
        }

        body.dark-theme .nav-group-items .nav-link {
            color: #e2e8f0;
        }

        body.dark-theme .nav-group-toggle::after {
            color: #f1f5f9;
        }

        body.dark-theme .nav-group-items .nav-link::before {
            background-color: #e2e8f0;
        }

        /* NEW: User-Friendly Form Styles */
        .form-section {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-section h5 {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-floating>label {
            padding: 1rem 0.75rem;
            font-weight: 500;
        }

        .form-floating>.form-control {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            min-height: 44px;
            /* Touch-friendly */
            padding: 1rem 0.75rem;
        }

        .form-floating>.form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(21, 158, 213, 0.25);
        }

        .form-floating>.form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-floating>.form-control.is-valid {
            border-color: #10b981;
        }

        /* NEW: Mandatory Field Indicators */
        .required {
            position: relative;
        }

        .required .asterisk {
            color: var(--required-color);
            font-weight: bold;
            margin-left: 0.25rem;
            font-size: 1.1em;
        }

        .required .asterisk::after {
            content: attr(title);
            /* Tooltip content from title attr */
            position: absolute;
            bottom: 100%;
            left: 0;
            background: #333;
            color: white;
            padding: 0.25rem;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 10;
        }

        .required:hover .asterisk::after,
        .required:focus-within .asterisk::after {
            opacity: 1;
        }

        /* Responsive Design (enhanced for forms/tables) */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .user-info {
                display: none;
            }

            /* NEW: Mobile form stacking */
            .row>.col-md-6 {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .navbar {
                padding: 1rem;
            }

            main {
                padding: 1.5rem 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .footer {
                padding: 1rem;
            }

            .navbar-brand {
                font-size: 1rem;
            }

            /* NEW: Mobile table scroll */
            .table-responsive {
                border-radius: 8px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Stat Widget Styles - Minimized, Professional (unchanged) */
        .stat-widget {
            height: 120px;
            /* Minimized height */
            padding: 0.75rem;
            /* Reduced padding for compactness */
            transition: transform 0.2s ease;
        }

        .stat-widget:hover {
            transform: translateY(-2px);
        }

        .stat-widget .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .stat-widget h5 {
            font-size: 1.5rem;
            /* Slightly larger for emphasis, but compact */
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #1f2937;
        }

        .stat-widget .status-text {
            font-size: 0.75rem;
            /* Smaller for minimized size */
            color: #6b7280;
            margin-bottom: 0;
            line-height: 1.2;
            max-height: 2.5em;
            /* Limit height for long texts */
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .stat-widget .details-link {
            font-size: 0.75rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .stat-widget .details-link:hover {
            text-decoration: underline;
        }

        .stat-widget .footer-text {
            font-size: 0.6875rem;
            color: #9ca3af;
        }

        /* Status-specific colors and icons (unchanged) */
        .status-need-surgery {
            border-left: 4px solid #f59e0b !important;
        }

        /* Orange */
        .status-need-surgery .stat-icon {
            background-color: #f59e0b;
            color: white;
        }

        .status-need-surgery .stat-icon i {
            color: white;
        }

        .status-sha-pending {
            border-left: 4px solid #eab308 !important;
        }

        /* Yellow */
        .status-sha-pending .stat-icon {
            background-color: #eab308;
            color: white;
        }

        .status-ready-schedule {
            border-left: 4px solid #10b981 !important;
        }

        /* Green */
        .status-ready-schedule .stat-icon {
            background-color: #10b981;
            color: white;
        }

        .status-scheduled {
            border-left: 4px solid #159ed5 !important;
        }

        /* Primary blue */
        .status-scheduled .stat-icon {
            background-color: #159ed5;
            color: white;
        }

        .status-completed {
            border-left: 4px solid #059669 !important;
        }

        /* Dark green */
        .status-completed .stat-icon {
            background-color: #059669;
            color: white;
        }

        .status-inactive {
            border-left: 4px solid #6b7280 !important;
        }

        /* Gray */
        .status-inactive .stat-icon {
            background-color: #6b7280;
            color: white;
        }

        .status-sha-rejected {
            border-left: 4px solid #ef4444 !important;
        }

        /* Red */
        .status-sha-rejected .stat-icon {
            background-color: #ef4444;
            color: white;
        }

        .status-cancelled {
            border-left: 4px solid #dc2626 !important;
        }

        /* Dark red */
        .status-cancelled .stat-icon {
            background-color: #dc2626;
            color: white;
        }

        .status-finalized {
            border-left: 4px solid #8b5cf6 !important;
        }

        /* Purple */
        .status-finalized .stat-icon {
            background-color: #8b5cf6;
            color: white;
        }

        /* NEW: Dark theme for forms */
        body.dark-theme .form-floating>.form-control {
            background-color: #252938;
            border-color: #2d3343;
            color: #e5e7eb;
        }

        body.dark-theme .form-floating>label {
            color: #a0aec0;
        }

        body.dark-theme .form-section {
            border-bottom-color: #2d3343;
        }

        /* NEW: Status badges for tables (used in dashboard) */
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .status-need-surgery .status-badge {
            background-color: #f59e0b;
            color: white;
        }

        .status-sha-pending .status-badge {
            background-color: #eab308;
            color: white;
        }

        .status-ready-schedule .status-badge {
            background-color: #10b981;
            color: white;
        }

        .status-scheduled .status-badge {
            background-color: #159ed5;
            color: white;
        }

        .status-completed .status-badge {
            background-color: #059669;
            color: white;
        }

        .status-inactive .status-badge {
            background-color: #6b7280;
            color: white;
        }

        .status-sha-rejected .status-badge {
            background-color: #ef4444;
            color: white;
        }

        .status-cancelled .status-badge {
            background-color: #dc2626;
            color: white;
        }

        .status-finalized .status-badge {
            background-color: #8b5cf6;
            color: white;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>
                <i class="fa-solid fa-hospital"></i>
                Theatre Booking
            </h4>
        </div>

        <div class="sidebar-nav">
            <a class="nav-link active" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-chart-line"></i>
                <span>Dashboard</span>
            </a>

            <a class="nav-link nav-group-toggle" href="#" data-bs-toggle="collapse"
                data-bs-target="#theatreStatusSubmenu" aria-expanded="false">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Surgery Pipeline</span>
            </a>

            <ul class="nav-group-items collapse" id="theatreStatusSubmenu">
                @php
                // Default list for all roles
                $statuses = [
                'Need Surgery',
                'SHA Submitted; Pending Approval',
                'Insurance Approved/Deposit Paid; Ready to Schedule',
                'Scheduled',
                'Completed',
                'Inactive',
                'SHA Rejected',
                ];

                // If user is a nurse, restrict list
                 if (auth()->check() && auth()->user()->isNurse()) {
                $statuses = [
                'Need Surgery',
                'SHA Submitted; Pending Approval',
                'Insurance Approved/Deposit Paid; Ready to Schedule',
                'Inactive',
                'SHA Rejected',
                ];
                }
                @endphp

                @foreach ($statuses as $status)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('surgeries.filter', ['status' => $status]) }}">
                        {{ $status }}
                    </a>
                </li>
                @endforeach
            </ul>

          @unless(auth()->check() && auth()->user()->isNurse())
            <a class="nav-link" href="{{ route('surgeries.finalized') }}">
                <i class="fa-solid fa-circle-check"></i>
                <span>Finalized Surgeries</span>
            </a>

            <a class="nav-link" href="{{ route('surgeries.rescheduled') }}">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Rescheduled Surgeries</span>
            </a>
            @endunless
            <a class="nav-link" href="{{ route('surgeries.cancelled') }}">
                <i class="fa-solid fa-ban"></i>
                <span>Cancelled Surgeries</span>
            </a>

            <div class="sidebar-divider"></div>

            @unless(auth()->check() && auth()->user()->isNurse())
            <a class="nav-link nav-group-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#reportsSubmenu"
                aria-expanded="false">
                <i class="fa-solid fa-file"></i>
                <span>Reports</span>
            </a>
            <ul class="nav-group-items collapse" id="reportsSubmenu">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Summary
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Detailed
                    </a>
                </li>
            </ul>
            @endunless

            @if(auth()->check() && auth()->user()->isAdmin())
            <a class="nav-link nav-group-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#accountsSubmenu"
                aria-expanded="false">
                <i class="fa-solid fa-users-gear"></i>
                <span>Accounts</span>
            </a>
            <ul class="nav-group-items collapse" id="accountsSubmenu">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}">
                        Users
                    </a>
                </li>
            </ul>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="container-fluid p-0">
                <div class="d-flex align-items-center gap-3">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <span class="navbar-brand mb-0">Admin Dashboard</span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <!-- Theme Toggle -->
                    <div class="theme-toggle" id="themeToggle">
                        <i class="fa-solid fa-moon"></i>
                    </div>

                    <!-- User Dropdown -->
                    <div class="dropdown">
                        @auth
                        <a class="user-dropdown dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('img/avator/user-profile.png') }}" class="user-avatar" alt="User">
                            <div class="user-info">
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <span class="user-role">Administrator</span>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa-solid fa-right-from-bracket"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                        @else
                        <a href="{{ route('login') }}" class="user-dropdown">
                            <img src="{{ asset('img/avator/user-profile.png') }}" class="user-avatar" alt="Guest">
                            <div class="user-info">
                                <span class="user-name">Guest</span>
                            </div>
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="footer-text">&copy; 2025 Theatre Dashboard. Powered with ❤ by Wechuli.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="footer-text">Version 1.0</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS (unchanged) -->
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const themeIcon = themeToggle.querySelector('i');

        // Apply saved theme
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-theme');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
        }

        themeToggle.addEventListener('click', function() {
            body.classList.toggle('dark-theme');

            if (body.classList.contains('dark-theme')) {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
                localStorage.setItem('theme', 'dark');
            } else {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
                localStorage.setItem('theme', 'light');
            }
        });

        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        }

        mobileMenuToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking a link on mobile
        if (window.innerWidth <= 992) {
            document.querySelectorAll('.sidebar .nav-link:not(.nav-group-toggle)').forEach(link => {
                link.addEventListener('click', toggleSidebar);
            });
        }
        // Sidebar Active Link + Dropdown Handling
        document.addEventListener("DOMContentLoaded", function() {
            const currentUrl = window.location.href;

            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                if (link.href && currentUrl.startsWith(link.href)) {
                    // Highlight active link
                    link.classList.add('active');

                    // If inside a dropdown, keep parent open
                    const submenu = link.closest('.collapse');
                    if (submenu) {
                        submenu.classList.add('show'); // keep submenu open
                        const toggle = document.querySelector(`[data-bs-target="#${submenu.id}"]`);
                        if (toggle) toggle.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        });
    </script>
</body>

</html>