<div class="header py-3 px-4">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Left side: Menu toggle and page title -->
        <div class="d-flex align-items-center">
            <!-- Mobile menu toggle -->
            <button class="btn btn-outline-secondary me-3 d-md-none" onclick="toggleMobileSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Desktop sidebar toggle -->
            <button class="btn btn-outline-secondary me-3 d-none d-md-block" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Page Title -->
            <div>
                <h4 class="mb-0 fw-semibold">{{ $pageTitle }}</h4>
                <small class="text-muted">{{ now()->format('l, F j, Y') }}</small>
            </div>
        </div>
        
        <!-- Right side: Search, notifications, and user menu -->
        <div class="d-flex align-items-center">
            <!-- Search -->
            <div class="me-3 d-none d-lg-block">
                <div class="input-group" style="width: 300px;">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Search bookings...">
                </div>
            </div>
            
            <!-- Notifications -->
            <div class="dropdown me-3">
                <button class="btn btn-outline-secondary position-relative" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-bell"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                    <li class="dropdown-header d-flex justify-content-between align-items-center">
                        <span>Notifications</span>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <small class="text-primary">{{ Auth::user()->unreadNotifications->count() }} new</small>
                        @endif
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    
                    @forelse(Auth::user()->notifications->take(5) as $notification)
                        <li>
                            <a class="dropdown-item py-2 {{ $notification->read_at ? '' : 'bg-light' }}" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-2">
                                        <i class="fas fa-info-circle text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li>
                            <div class="dropdown-item-text text-center py-3">
                                <i class="fas fa-bell-slash text-muted fs-4"></i>
                                <div class="mt-2">No notifications</div>
                            </div>
                        </li>
                    @endforelse
                    
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-center" href="{{ route('requester.notifications') }}">
                            View all notifications
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- User Profile Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="fas fa-user text-white small"></i>
                    </div>
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down ms-2"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">
                        <div class="fw-semibold">{{ Auth::user()->name }}</div>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-cog me-2"></i>
                            Profile Settings
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('requester.bookings') }}">
                            <i class="fas fa-calendar-check me-2"></i>
                            My Bookings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button class="dropdown-item" wire:click="$dispatch('logout')">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('logout', () => {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = '{{ route('logout') }}';
        }
    });
</script>
@endscript