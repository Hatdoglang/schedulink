<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Admin Dashboard') }} - Admin Panel</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/link.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Custom Admin Styles -->
    <style>
        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }
        .admin-sidebar {
            min-height: calc(100vh - 56px);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .admin-content {
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
        }
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1) !important;
            transform: translateX(5px);
        }
        .nav-link.active {
            background-color: rgba(255,255,255,0.2) !important;
            border-radius: 0.375rem;
            font-weight: 600;
        }
        .text-gray-800 {
            color: #1f2937 !important;
        }
        .text-xs {
            font-size: 0.75rem;
        }
        .navbar-nav .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            border-radius: 0.375rem;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                min-height: auto;
            }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Admin Navigation -->
    <livewire:admin.layout.navigation />

    <!-- Main Content -->
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar">
                <div class="p-3">
                    <div class="text-white mb-4">
                        <h6 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i>
                            Admin Panel
                        </h6>
                        <small class="opacity-75">{{ auth()->user()->full_name }}</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                        
                        <a class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                           href="/admin/users">
                            <i class="fas fa-users me-2"></i>
                            Manage Users
                        </a>
                        
                        <a class="nav-link text-white {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" 
                           href="/admin/bookings">
                            <i class="fas fa-calendar-alt me-2"></i>
                            All Bookings
                        </a>
                        
                        <a class="nav-link text-white {{ request()->routeIs('admin.asset-types.*') ? 'active' : '' }}" 
                           href="/admin/asset-types">
                            <i class="fas fa-car me-2"></i>
                            Manage Assets
                        </a>
                        
                        <a class="nav-link text-white {{ request()->routeIs('admin.approvers.*') ? 'active' : '' }}" 
                           href="/admin/approvers">
                            <i class="fas fa-user-check me-2"></i>
                            Approvers
                        </a>
                        
                        <a class="nav-link text-white {{ request()->routeIs('admin.analytics') ? 'active' : '' }}" 
                           href="/admin/analytics">
                            <i class="fas fa-chart-line me-2"></i>
                            Analytics
                        </a>
                        
                        <hr class="border-light opacity-25 my-3">
                        
                        <a class="nav-link text-white" href="{{ route('profile') }}">
                            <i class="fas fa-user-cog me-2"></i>
                            My Profile
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-md-9 col-lg-10 admin-content">
                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>