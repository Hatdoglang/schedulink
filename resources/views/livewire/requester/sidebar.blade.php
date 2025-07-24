<div class="sidebar" id="sidebar">
    <!-- Logo/Brand -->
    <div class="d-flex align-items-center p-3 border-bottom border-secondary">
        <div class="text-white">
            <i class="fas fa-calendar-alt fs-4 me-2"></i>
            <span class="fw-bold sidebar-text">Schedulink</span>
        </div>
    </div>
    
    <!-- User Info -->
    <div class="p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center text-white">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                <i class="fas fa-user"></i>
            </div>
            <div class="sidebar-text">
                <div class="fw-semibold">{{ Auth::user()->name }}</div>
                <small class="text-light opacity-75">Requester</small>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-grow-1 py-3">
        <ul class="list-unstyled px-2">
            <!-- Dashboard -->
            <li class="mb-1">
                <a href="{{ route('requester.dashboard') }}" 
                   class="nav-link d-flex align-items-center px-3 py-2 text-white text-decoration-none rounded {{ request()->routeIs('requester.dashboard') ? 'bg-primary' : '' }} sidebar-link">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            
            <!-- My Bookings -->
            <li class="mb-1">
                <a href="{{ route('requester.bookings') }}" 
                   class="nav-link d-flex align-items-center px-3 py-2 text-white text-decoration-none rounded {{ request()->routeIs('requester.bookings*') ? 'bg-primary' : '' }} sidebar-link">
                    <i class="fas fa-calendar-check me-3"></i>
                    <span class="sidebar-text">My Bookings</span>
                </a>
            </li>
            
            <!-- New Booking -->
            <li class="mb-1">
                <a href="{{ route('requester.bookings.create') }}" 
                   class="nav-link d-flex align-items-center px-3 py-2 text-white text-decoration-none rounded {{ request()->routeIs('requester.bookings.create') ? 'bg-primary' : '' }} sidebar-link">
                    <i class="fas fa-plus-circle me-3"></i>
                    <span class="sidebar-text">New Booking</span>
                </a>
            </li>
            
            <!-- Calendar -->
            <li class="mb-1">
                <a href="{{ route('requester.calendar') }}" 
                   class="nav-link d-flex align-items-center px-3 py-2 text-white text-decoration-none rounded {{ request()->routeIs('requester.calendar') ? 'bg-primary' : '' }} sidebar-link">
                    <i class="fas fa-calendar me-3"></i>
                    <span class="sidebar-text">Calendar</span>
                </a>
            </li>
            
            <!-- Profile -->
            <li class="mb-1">
                <a href="{{ route('profile.edit') }}" 
                   class="nav-link d-flex align-items-center px-3 py-2 text-white text-decoration-none rounded {{ request()->routeIs('profile.*') ? 'bg-primary' : '' }} sidebar-link">
                    <i class="fas fa-user-cog me-3"></i>
                    <span class="sidebar-text">Profile</span>
                </a>
            </li>
            
            <!-- Notifications -->
            <li class="mb-1">
                <a href="{{ route('requester.notifications') }}" 
                   class="nav-link d-flex align-items-center px-3 py-2 text-white text-decoration-none rounded {{ request()->routeIs('requester.notifications') ? 'bg-primary' : '' }} sidebar-link">
                    <i class="fas fa-bell me-3"></i>
                    <span class="sidebar-text">Notifications</span>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger ms-auto sidebar-text">{{ Auth::user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- Bottom Actions -->
    <div class="p-3 border-top border-secondary">
        <button wire:click="logout" 
                class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center">
            <i class="fas fa-sign-out-alt me-2"></i>
            <span class="sidebar-text">Logout</span>
        </button>
    </div>
</div>

<style>
    .sidebar-link:hover {
        background-color: rgba(59, 130, 246, 0.3) !important;
    }
    
    .sidebar.collapsed .sidebar-text {
        display: none;
    }
    
    .sidebar.collapsed .nav-link {
        justify-content: center;
    }
    
    .sidebar.collapsed .badge {
        display: none;
    }
</style>