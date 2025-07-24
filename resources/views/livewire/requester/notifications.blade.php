<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 fw-semibold">Notifications</h4>
                            <small class="text-muted">
                                @if($unreadCount > 0)
                                    You have {{ $unreadCount }} unread notification{{ $unreadCount > 1 ? 's' : '' }}
                                @else
                                    All notifications read
                                @endif
                            </small>
                        </div>
                        @if($unreadCount > 0)
                            <button wire:click="markAllAsRead" class="btn btn-outline-primary">
                                <i class="fas fa-check-double me-2"></i>Mark All as Read
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <button wire:click="setFilter('all')" 
                                    class="nav-link {{ $filter === 'all' ? 'active' : '' }}">
                                All Notifications
                            </button>
                        </li>
                        <li class="nav-item">
                            <button wire:click="setFilter('unread')" 
                                    class="nav-link {{ $filter === 'unread' ? 'active' : '' }}">
                                Unread
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item">
                            <button wire:click="setFilter('read')" 
                                    class="nav-link {{ $filter === 'read' ? 'active' : '' }}">
                                Read
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                @php
                                    $isUnread = is_null($notification->read_at);
                                    $data = $notification->data;
                                    $title = $data['title'] ?? 'Notification';
                                    $message = $data['message'] ?? $data['body'] ?? 'No message content';
                                    $type = $data['type'] ?? 'info';
                                    $bookingId = $data['booking_id'] ?? null;
                                    
                                    $typeIcons = [
                                        'success' => 'fas fa-check-circle text-success',
                                        'warning' => 'fas fa-exclamation-triangle text-warning',
                                        'danger' => 'fas fa-times-circle text-danger',
                                        'info' => 'fas fa-info-circle text-primary',
                                        'booking_approved' => 'fas fa-check-circle text-success',
                                        'booking_rejected' => 'fas fa-times-circle text-danger',
                                        'booking_cancelled' => 'fas fa-ban text-secondary',
                                    ];
                                    
                                    $icon = $typeIcons[$type] ?? 'fas fa-bell text-primary';
                                @endphp
                                
                                <div class="list-group-item border-0 {{ $isUnread ? 'bg-light' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <!-- Notification Icon -->
                                        <div class="flex-shrink-0 me-3 mt-1">
                                            <i class="{{ $icon }}"></i>
                                        </div>
                                        
                                        <!-- Notification Content -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-semibold {{ $isUnread ? 'text-primary' : '' }}">
                                                        {{ $title }}
                                                        @if($isUnread)
                                                            <span class="badge bg-primary ms-2">New</span>
                                                        @endif
                                                    </h6>
                                                    <p class="mb-2 text-muted">{{ $message }}</p>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                
                                                <!-- Actions -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @if($isUnread)
                                                            <li>
                                                                <button wire:click="markAsRead('{{ $notification->id }}')" 
                                                                        class="dropdown-item">
                                                                    <i class="fas fa-check me-2"></i>Mark as Read
                                                                </button>
                                                            </li>
                                                        @endif
                                                        @if($bookingId)
                                                            <li>
                                                                <a href="{{ route('requester.bookings.show', $bookingId) }}" 
                                                                   class="dropdown-item">
                                                                    <i class="fas fa-eye me-2"></i>View Booking
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button wire:click="deleteNotification('{{ $notification->id }}')" 
                                                                    class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this notification?')">
                                                                <i class="fas fa-trash me-2"></i>Delete
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        @if($notifications->hasPages())
                            <div class="card-footer bg-white border-top-0">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash text-muted fs-1"></i>
                            <div class="mt-3">
                                <h6 class="text-muted">No notifications</h6>
                                <p class="text-muted">
                                    @if($filter === 'unread')
                                        You don't have any unread notifications.
                                    @elseif($filter === 'read')
                                        You don't have any read notifications.
                                    @else
                                        You don't have any notifications yet.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Quick Actions</h6>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('requester.bookings') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-calendar-check me-2"></i>View My Bookings
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('requester.bookings.create') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-plus me-2"></i>Create New Booking
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('requester.dashboard') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-tachometer-alt me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>