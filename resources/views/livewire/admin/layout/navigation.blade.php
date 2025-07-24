<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <!-- Brand/Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/gmall.png') }}" alt="Logo" height="30" class="me-2">
            <span class="fw-bold">Admin Panel</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" 
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Items -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            <!-- Left Side Navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                       href="/admin/users">
                        <i class="fas fa-users me-1"></i>
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" 
                       href="/admin/bookings">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Bookings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.asset-types.*') ? 'active' : '' }}" 
                       href="/admin/asset-types">
                        <i class="fas fa-car me-1"></i>
                        Assets
                    </a>
                </li>
            </ul>

            <!-- Right Side Navigation -->
            <ul class="navbar-nav">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" style="font-size: 0.6rem;">3</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <li><a class="dropdown-item" href="#"><small>New booking pending approval</small></a></li>
                        <li><a class="dropdown-item" href="#"><small>User registration request</small></a></li>
                        <li><a class="dropdown-item" href="#"><small>System maintenance scheduled</small></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#"><small>View all notifications</small></a></li>
                    </ul>
                </li>

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary rounded-circle p-1 me-2">
                            <i class="fas fa-user-shield text-white"></i>
                        </div>
                        <span class="d-none d-md-inline">{{ auth()->user()->full_name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><h6 class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-2 me-2">
                                    <i class="fas fa-user-shield text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ auth()->user()->full_name }}</div>
                                    <small class="text-muted">Administrator</small>
                                </div>
                            </div>
                        </h6></li>
                        <li><hr class="dropdown-divider"></li>
                        
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fas fa-user-cog me-2"></i>
                                My Profile
                            </a>
                        </li>
                        
                        <li>
                            <a class="dropdown-item" href="/admin/analytics">
                                <i class="fas fa-chart-line me-2"></i>
                                Analytics
                            </a>
                        </li>
                        
                        <li>
                            <a class="dropdown-item" href="/admin/approvers">
                                <i class="fas fa-user-check me-2"></i>
                                Manage Approvers
                            </a>
                        </li>
                        
                        <li><hr class="dropdown-divider"></li>
                        
                        <li>
                            <button wire:click="logout" class="dropdown-item text-danger" 
                                    wire:confirm="Are you sure you want to logout?">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </button>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Loading Indicator -->
<div wire:loading wire:target="logout" class="position-fixed top-50 start-50 translate-middle">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Logging out...</span>
    </div>
</div>