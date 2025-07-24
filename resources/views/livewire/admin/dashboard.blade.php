<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Admin Dashboard</h2>
                <button wire:click="refreshData" class="btn btn-outline-primary">
                    <i class="fas fa-sync-alt"></i> Refresh Data
                </button>
            </div>
        </div>
    </div>

    <!-- System Health Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $systemHealth['total_users'] ?? 0 }}</h3>
                            <p class="card-text">Total Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
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
                            <h3 class="card-title">{{ $systemHealth['active_users'] ?? 0 }}</h3>
                            <p class="card-text">Active Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $systemHealth['total_assets'] ?? 0 }}</h3>
                            <p class="card-text">Total Assets</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-car fa-2x"></i>
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
                            <h3 class="card-title">{{ $systemHealth['total_bookings'] ?? 0 }}</h3>
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
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $systemHealth['utilization_rate'] ?? 0 }}%</h3>
                            <p class="card-text">Utilization</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-pie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Booking Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $bookingStatistics['pending'] ?? 0 }}</h4>
                                <p class="mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-success">{{ $bookingStatistics['approved'] ?? 0 }}</h4>
                                <p class="mb-0">Approved</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-danger">{{ $bookingStatistics['rejected'] ?? 0 }}</h4>
                                <p class="mb-0">Rejected</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-secondary">{{ $bookingStatistics['cancelled'] ?? 0 }}</h4>
                                <p class="mb-0">Cancelled</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-md-8">
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
                                        <th>User</th>
                                        <th>Asset</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td>
                                                <strong>{{ $booking->user->full_name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $booking->user->email ?? 'N/A' }}</small>
                                            </td>
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
                                            <td>
                                                <a href="/admin/bookings/{{ $booking->id }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/admin/bookings" class="btn btn-outline-primary btn-sm">View All Bookings</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No bookings found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Most Active Users -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Most Active Users</h5>
                </div>
                <div class="card-body">
                    @if(count($mostActiveUsers) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($mostActiveUsers as $user)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <strong>{{ $user->full_name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $user->bookings_count }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="/admin/users" class="btn btn-outline-primary btn-sm">Manage Users</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No active users found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Utilization -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Asset Utilization by Type</h5>
                </div>
                <div class="card-body">
                    @if(count($assetUtilization) > 0)
                        @foreach($assetUtilization as $asset)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ $asset->name }}</strong>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-info">{{ $asset->total_bookings }} total</span>
                                    <span class="badge bg-success">{{ $asset->approved_bookings }} approved</span>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="/admin/asset-types" class="btn btn-outline-primary btn-sm">Manage Assets</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No asset data available</p>
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
                                        <th>User</th>
                                        <th>Asset</th>
                                        <th>Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingBookings as $booking)
                                        <tr>
                                            <td>
                                                <small>{{ $booking->user->full_name ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $booking->assetDetail->asset_name ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d') }}</small><br>
                                                <small class="text-muted">{{ $booking->time_from }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/admin/bookings?status=approved" class="btn btn-outline-success btn-sm">View All Approved</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No upcoming bookings</p>
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
                    <h5 class="card-title mb-0">Admin Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <a href="/admin/users" class="btn btn-primary w-100">
                                <i class="fas fa-users"></i> Manage Users
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="/admin/bookings" class="btn btn-outline-primary w-100">
                                <i class="fas fa-calendar-alt"></i> All Bookings
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="/admin/asset-types" class="btn btn-outline-info w-100">
                                <i class="fas fa-car"></i> Manage Assets
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="/admin/approvers" class="btn btn-outline-warning w-100">
                                <i class="fas fa-user-check"></i> Approvers
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="/admin/analytics" class="btn btn-outline-success w-100">
                                <i class="fas fa-chart-line"></i> Analytics
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="/profile" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-user-cog"></i> My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>