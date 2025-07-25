<div>
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Bookings</h1>
        <a href="{{ route('requester.bookings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Booking
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" placeholder="Search bookings..." wire:model.live="search">
                </div>
                <div class="col-md-2">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select class="form-select" id="statusFilter" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="dateFrom" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="dateFrom" wire:model.live="dateFrom">
                </div>
                <div class="col-md-2">
                    <label for="dateTo" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="dateTo" wire:model.live="dateTo">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary" wire:click="clearFilters">
                        <i class="fas fa-times"></i> Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-body">
            @if ($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <button type="button" class="btn btn-link p-0 text-decoration-none" wire:click="sortBy('scheduled_date')">
                                        Date
                                        @if ($sortBy === 'scheduled_date')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>
                                    <button type="button" class="btn btn-link p-0 text-decoration-none" wire:click="sortBy('time_from')">
                                        Time
                                        @if ($sortBy === 'time_from')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>Asset</th>
                                <th>Purpose</th>
                                <th>Destination</th>
                                <th>
                                    <button type="button" class="btn btn-link p-0 text-decoration-none" wire:click="sortBy('status')">
                                        Status
                                        @if ($sortBy === 'status')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </button>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d, Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($booking->time_from)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($booking->time_to)->format('h:i A') }}
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $booking->assetType->name ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $booking->assetDetail->name ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ Str::limit($booking->purpose, 30) }}</td>
                                    <td>{{ Str::limit($booking->destination, 25) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'cancelled' => 'secondary',
                                                'completed' => 'info'
                                            ];
                                            $color = $statusColors[$booking->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if (in_array($booking->status, ['pending']))
                                                <button type="button" class="btn btn-outline-secondary" title="Edit Booking">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            @if (in_array($booking->status, ['pending', 'approved']))
                                                <button type="button" 
                                                        class="btn btn-outline-danger" 
                                                        title="Cancel Booking"
                                                        wire:click="cancelBooking({{ $booking->id }})"
                                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} results
                    </div>
                    <div>
                        {{ $bookings->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No bookings found</h5>
                    <p class="text-muted">
                        @if ($search || $statusFilter || $dateFrom || $dateTo)
                            Try adjusting your filters to find more bookings.
                        @else
                            You haven't made any bookings yet. 
                        @endif
                    </p>
                    @if (!$search && !$statusFilter && !$dateFrom && !$dateTo)
                        <a href="{{ route('requester.bookings.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Your First Booking
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>