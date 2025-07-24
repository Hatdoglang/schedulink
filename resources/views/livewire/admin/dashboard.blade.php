<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 mb-1 text-gray-800">Admin Dashboard</h1>
                    <p class="text-muted mb-0">Welcome back! Here's what's happening with your system today.</p>
                </div>
                <button wire:click="refreshData" class="btn btn-outline-primary d-flex align-items-center">
                    <i class="fas fa-sync-alt me-2"></i> Refresh Data
                </button>
            </div>
        </div>
    </div>

    <!-- System Health Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($systemHealth['total_users'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-primary bg-gradient rounded-circle p-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Active Users</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($systemHealth['active_users'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-success bg-gradient rounded-circle p-3">
                                <i class="fas fa-user-check text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Assets</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($systemHealth['total_assets'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-info bg-gradient rounded-circle p-3">
                                <i class="fas fa-car text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Bookings</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($systemHealth['total_bookings'] ?? 0) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-warning bg-gradient rounded-circle p-3">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-secondary text-uppercase mb-1">Utilization</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $systemHealth['utilization_rate'] ?? 0 }}%</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-secondary bg-gradient rounded-circle p-3">
                                <i class="fas fa-chart-pie text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-gradient rounded-circle p-2 me-3">
                            <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Booking Statistics</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                <div class="h3 mb-1 fw-bold text-warning">{{ number_format($bookingStatistics['pending'] ?? 0) }}</div>
                                <div class="text-warning fw-semibold">Pending</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25">
                                <div class="h3 mb-1 fw-bold text-success">{{ number_format($bookingStatistics['approved'] ?? 0) }}</div>
                                <div class="text-success fw-semibold">Approved</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                                <div class="h3 mb-1 fw-bold text-danger">{{ number_format($bookingStatistics['rejected'] ?? 0) }}</div>
                                <div class="text-danger fw-semibold">Rejected</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3 bg-secondary bg-opacity-10 border border-secondary border-opacity-25">
                                <div class="h3 mb-1 fw-bold text-secondary">{{ number_format($bookingStatistics['cancelled'] ?? 0) }}</div>
                                <div class="text-secondary fw-semibold">Cancelled</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Bookings -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-gradient rounded-circle p-2 me-3">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <h5 class="card-title mb-0 fw-bold">Recent Bookings</h5>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ count($recentBookings) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($recentBookings) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">User</th>
                                        <th class="border-0 fw-semibold">Asset</th>
                                        <th class="border-0 fw-semibold">Date</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                        <th class="border-0 fw-semibold text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-gradient rounded-circle p-2 me-3">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $booking->user->full_name ?? 'N/A' }}</div>
                                                        <div class="text-muted small">{{ $booking->user->email ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-semibold">{{ $booking->assetType->name ?? 'N/A' }}</div>
                                                    <div class="text-muted small">{{ $booking->assetDetail->asset_name ?? 'N/A' }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d, Y') }}</div>
                                                    <div class="text-muted small">{{ $booking->time_from }} - {{ $booking->time_to }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($booking->status === 'pending')
                                                    <span class="badge bg-warning rounded-pill">
                                                        <i class="fas fa-clock me-1"></i>Pending
                                                    </span>
                                                @elseif($booking->status === 'approved')
                                                    <span class="badge bg-success rounded-pill">
                                                        <i class="fas fa-check me-1"></i>Approved
                                                    </span>
                                                @elseif($booking->status === 'rejected')
                                                    <span class="badge bg-danger rounded-pill">
                                                        <i class="fas fa-times me-1"></i>Rejected
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary rounded-pill">{{ ucfirst($booking->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="/admin/bookings/{{ $booking->id }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/admin/bookings" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-calendar-alt me-2"></i>View All Bookings
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle p-4 d-inline-flex mb-3">
                                <i class="fas fa-calendar-times fa-3x text-muted"></i>
                            </div>
                            <h6 class="text-muted">No bookings found</h6>
                            <p class="text-muted small">New bookings will appear here when created.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Most Active Users -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-gradient rounded-circle p-2 me-3">
                                <i class="fas fa-trophy text-white"></i>
                            </div>
                            <h5 class="card-title mb-0 fw-bold">Most Active Users</h5>
                        </div>
                        <span class="badge bg-success rounded-pill">Top {{ count($mostActiveUsers) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($mostActiveUsers) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($mostActiveUsers as $index => $user)
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($index === 0)
                                                <div class="bg-warning bg-gradient rounded-circle p-2">
                                                    <i class="fas fa-crown text-white"></i>
                                                </div>
                                            @elseif($index === 1)
                                                <div class="bg-secondary bg-gradient rounded-circle p-2">
                                                    <i class="fas fa-medal text-white"></i>
                                                </div>
                                            @else
                                                <div class="bg-light rounded-circle p-2">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $user->full_name ?? 'N/A' }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary rounded-pill fs-6">{{ $user->bookings_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="/admin/users" class="btn btn-success rounded-pill px-4">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle p-4 d-inline-flex mb-3">
                                <i class="fas fa-users fa-3x text-muted"></i>
                            </div>
                            <h6 class="text-muted">No active users found</h6>
                            <p class="text-muted small">User activity will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Utilization & Upcoming Bookings -->
    <div class="row g-4 mt-0">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-gradient rounded-circle p-2 me-3">
                            <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Asset Utilization by Type</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($assetUtilization) > 0)
                        @foreach($assetUtilization as $asset)
                            <div class="d-flex align-items-center justify-content-between p-3 mb-3 bg-light rounded-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-gradient rounded-circle p-2 me-3">
                                        <i class="fas fa-car text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $asset->name }}</div>
                                        <div class="text-muted small">Asset Type</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-info rounded-pill">{{ $asset->total_bookings }} total</span>
                                        <span class="badge bg-success rounded-pill">{{ $asset->approved_bookings }} approved</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="/admin/asset-types" class="btn btn-info rounded-pill px-4">
                                <i class="fas fa-cogs me-2"></i>Manage Assets
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle p-4 d-inline-flex mb-3">
                                <i class="fas fa-chart-bar fa-3x text-muted"></i>
                            </div>
                            <h6 class="text-muted">No asset data available</h6>
                            <p class="text-muted small">Asset utilization will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-gradient rounded-circle p-2 me-3">
                                <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <h5 class="card-title mb-0 fw-bold">Upcoming Approved Bookings</h5>
                        </div>
                        <span class="badge bg-warning rounded-pill">{{ count($upcomingBookings) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($upcomingBookings) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">User</th>
                                        <th class="border-0 fw-semibold">Asset</th>
                                        <th class="border-0 fw-semibold">Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingBookings as $booking)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success bg-gradient rounded-circle p-1 me-2">
                                                        <i class="fas fa-user text-white small"></i>
                                                    </div>
                                                    <span class="fw-semibold small">{{ $booking->user->full_name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold small">{{ $booking->assetDetail->asset_name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-semibold small">{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d') }}</div>
                                                    <div class="text-muted small">{{ $booking->time_from }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/admin/bookings?status=approved" class="btn btn-warning rounded-pill px-4">
                                <i class="fas fa-calendar-check me-2"></i>View All Approved
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle p-4 d-inline-flex mb-3">
                                <i class="fas fa-calendar-check fa-3x text-muted"></i>
                            </div>
                            <h6 class="text-muted">No upcoming bookings</h6>
                            <p class="text-muted small">Approved bookings will appear here.</p>
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
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary bg-gradient rounded-circle p-2 me-3">
                            <i class="fas fa-bolt text-white"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Admin Quick Actions</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <a href="/admin/users" class="btn btn-primary w-100 py-3 rounded-3 text-decoration-none">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-users fa-lg mb-2"></i>
                                    <span class="fw-semibold">Manage Users</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <a href="/admin/bookings" class="btn btn-outline-primary w-100 py-3 rounded-3 text-decoration-none">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-calendar-alt fa-lg mb-2"></i>
                                    <span class="fw-semibold">All Bookings</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <a href="/admin/asset-types" class="btn btn-outline-info w-100 py-3 rounded-3 text-decoration-none">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-car fa-lg mb-2"></i>
                                    <span class="fw-semibold">Manage Assets</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <a href="/admin/approvers" class="btn btn-outline-warning w-100 py-3 rounded-3 text-decoration-none">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-user-check fa-lg mb-2"></i>
                                    <span class="fw-semibold">Approvers</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <a href="/admin/analytics" class="btn btn-outline-success w-100 py-3 rounded-3 text-decoration-none">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-chart-line fa-lg mb-2"></i>
                                    <span class="fw-semibold">Analytics</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                            <a href="/profile" class="btn btn-outline-secondary w-100 py-3 rounded-3 text-decoration-none">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-user-cog fa-lg mb-2"></i>
                                    <span class="fw-semibold">My Profile</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>