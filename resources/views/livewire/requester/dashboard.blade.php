<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-calendar-alt text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Total Bookings</div>
                            <div class="fs-4 fw-bold">{{ $statistics['total_bookings'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Pending</div>
                            <div class="fs-4 fw-bold">{{ $statistics['pending_bookings'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Approved</div>
                            <div class="fs-4 fw-bold">{{ $statistics['approved_bookings'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-times-circle text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Rejected</div>
                            <div class="fs-4 fw-bold">{{ $statistics['rejected_bookings'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Recent Bookings</h5>
                        <button wire:click="refreshData" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentBookings && $recentBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Asset</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Time</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                                                        <i class="fas fa-cube text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $booking->assetType->name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $booking->assetDetail->name ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->time_from)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->time_to)->format('H:i') }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'approved' => 'success',
                                                        'rejected' => 'danger',
                                                        'cancelled' => 'secondary'
                                                    ];
                                                    $color = $statusColors[$booking->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ ucfirst($booking->status) }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($booking->status === 'pending')
                                                        <button class="btn btn-outline-danger" title="Cancel">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times text-muted fs-1"></i>
                            <div class="mt-3">
                                <h6 class="text-muted">No bookings yet</h6>
                                <p class="text-muted">Start by creating your first booking</p>
                                <a href="{{ route('requester.bookings.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>New Booking
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Upcoming Bookings & Quick Actions -->
        <div class="col-lg-4">
            <!-- Upcoming Bookings -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-semibold">Upcoming Bookings</h5>
                </div>
                <div class="card-body">
                    @if($upcomingBookings && $upcomingBookings->count() > 0)
                        @foreach($upcomingBookings as $booking)
                            <div class="d-flex align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-calendar-check text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $booking->assetType->name ?? 'N/A' }}</div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d') }} at 
                                        {{ \Carbon\Carbon::parse($booking->time_from)->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('requester.calendar') }}" class="btn btn-outline-primary btn-sm">
                                View Calendar
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar text-muted fs-4"></i>
                            <div class="mt-2">
                                <small class="text-muted">No upcoming bookings</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-semibold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('requester.bookings.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>New Booking
                        </a>
                        <a href="{{ route('requester.bookings') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View All Bookings
                        </a>
                        <a href="{{ route('requester.calendar') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-calendar me-2"></i>Calendar View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
    