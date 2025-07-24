<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">My Booking Dashboard</h2>
                <button wire:click="refreshData" class="btn btn-outline-primary">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $statistics['total_bookings'] ?? 0 }}</h3>
                            <p class="card-text">Total Bookings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $statistics['pending_bookings'] ?? 0 }}</h3>
                            <p class="card-text">Pending</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $statistics['approved_bookings'] ?? 0 }}</h3>
                            <p class="card-text">Approved</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $statistics['rejected_bookings'] ?? 0 }}</h3>
                            <p class="card-text">Rejected</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $statistics['cancelled_bookings'] ?? 0 }}</h3>
                            <p class="card-text">Cancelled</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-ban fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Bookings</h5>
                </div>
                <div class="card-body">
                    @if(count($recentBookings) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Asset</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td>
                                                <strong>{{ $booking->assetType->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $booking->assetDetail->asset_name ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $booking->time_from }} - {{ $booking->time_to }}</small>
                                            </td>
                                            <td>
                                                @if($booking->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($booking->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($booking->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/requester/bookings" class="btn btn-outline-primary btn-sm">View All Bookings</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No bookings found</p>
                            <a href="/requester/bookings/create" class="btn btn-primary">Create Your First Booking</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upcoming Approved Bookings</h5>
                </div>
                <div class="card-body">
                    @if(count($upcomingBookings) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Asset</th>
                                        <th>Date & Time</th>
                                        <th>Destination</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingBookings as $booking)
                                        <tr>
                                            <td>
                                                <strong>{{ $booking->assetType->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $booking->assetDetail->asset_name ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $booking->time_from }} - {{ $booking->time_to }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $booking->destination }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/requester/bookings?status=approved" class="btn btn-outline-success btn-sm">View All Approved</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No upcoming approved bookings</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="/requester/bookings/create" class="btn btn-primary w-100">
                                <i class="fas fa-plus"></i> New Booking
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="/requester/bookings" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list"></i> My Bookings
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="/requester/bookings?status=pending" class="btn btn-outline-warning w-100">
                                <i class="fas fa-clock"></i> Pending Bookings
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="/profile" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>