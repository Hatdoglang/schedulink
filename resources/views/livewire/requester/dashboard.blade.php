<!-- Include Sidebar Navigation -->
@include('livewire.requester.sidebar')

<!-- Main Content Area -->
<div class="main-content">
    <div class="container-fluid p-4">
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
            <!-- Calendar Widget -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">Calendar View</h5>
                            <div class="btn-group btn-group-sm">
                                <button wire:click="refreshData" class="btn btn-outline-primary">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                                <a href="{{ route('requester.calendar') }}" class="btn btn-primary">
                                    <i class="fas fa-expand"></i> Full Calendar
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mini Calendar -->
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" style="font-size: 0.875rem;">
                                <!-- Days of Week Header -->
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center py-2 small">Sun</th>
                                        <th class="text-center py-2 small">Mon</th>
                                        <th class="text-center py-2 small">Tue</th>
                                        <th class="text-center py-2 small">Wed</th>
                                        <th class="text-center py-2 small">Thu</th>
                                        <th class="text-center py-2 small">Fri</th>
                                        <th class="text-center py-2 small">Sat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($calendarData ?? false)
                                        @foreach($calendarData['weeks'] as $week)
                                            <tr style="height: 80px;">
                                                @foreach($week as $day)
                                                    @php
                                                        $dayString = $day['date'];
                                                        $dayBookings = $day['bookings'] ?? collect();
                                                        $isCurrentMonth = $day['isCurrentMonth'];
                                                        $isToday = $day['isToday'];
                                                    @endphp
                                                    <td class="position-relative p-2 align-top
                                                        {{ !$isCurrentMonth ? 'text-muted bg-light' : '' }}
                                                        {{ $isToday ? 'bg-primary bg-opacity-10' : '' }}">
                                                        
                                                        <!-- Day Number -->
                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                            <span class="fw-semibold {{ $isToday ? 'text-primary' : '' }}" style="font-size: 0.8rem;">
                                                                {{ $day['dayNumber'] }}
                                                            </span>
                                                            @if($isToday)
                                                                <span class="badge bg-primary" style="font-size: 0.6rem;">Today</span>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Bookings for this day -->
                                                        @if($dayBookings->count() > 0)
                                                            <div class="calendar-events">
                                                                @foreach($dayBookings->take(2) as $booking)
                                                                    @php
                                                                        $statusColors = [
                                                                            'pending' => 'warning',
                                                                            'approved' => 'success',
                                                                            'rejected' => 'danger',
                                                                            'cancelled' => 'secondary'
                                                                        ];
                                                                        $color = $statusColors[$booking->status] ?? 'secondary';
                                                                    @endphp
                                                                    <div class="calendar-event mb-1" style="font-size: 0.65rem;">
                                                                        <div class="bg-{{ $color }} bg-opacity-25 border border-{{ $color }} rounded px-1 py-0">
                                                                            <div class="text-{{ $color == 'warning' ? 'dark' : $color }}">
                                                                                {{ \Carbon\Carbon::parse($booking->time_from)->format('H:i') }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                
                                                                @if($dayBookings->count() > 2)
                                                                    <div class="small text-muted" style="font-size: 0.6rem;">
                                                                        +{{ $dayBookings->count() - 2 }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-calendar text-muted fs-3"></i>
                                                <div class="mt-2">
                                                    <h6 class="text-muted">Calendar Loading...</h6>
                                                    <p class="text-muted small">Your bookings calendar will appear here</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Calendar Legend -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-center gap-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-25 border border-warning rounded me-1" style="width: 12px; height: 12px;"></div>
                                        <span class="small">Pending</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-25 border border-success rounded me-1" style="width: 12px; height: 12px;"></div>
                                        <span class="small">Approved</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-25 border border-danger rounded me-1" style="width: 12px; height: 12px;"></div>
                                        <span class="small">Rejected</span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
</div>