<div class="container-fluid">
    <!-- Calendar Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 fw-semibold">{{ $monthName }}</h4>
                            <small class="text-muted">My Booking Calendar</small>
                        </div>
                        <div class="btn-group">
                            <button wire:click="previousMonth" class="btn btn-outline-primary">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button wire:click="today" class="btn btn-primary">
                                Today
                            </button>
                            <button wire:click="nextMonth" class="btn btn-outline-primary">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <!-- Calendar Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" style="height: 600px;">
                            <!-- Days of Week Header -->
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center py-3" style="width: 14.28%;">Sunday</th>
                                    <th class="text-center py-3" style="width: 14.28%;">Monday</th>
                                    <th class="text-center py-3" style="width: 14.28%;">Tuesday</th>
                                    <th class="text-center py-3" style="width: 14.28%;">Wednesday</th>
                                    <th class="text-center py-3" style="width: 14.28%;">Thursday</th>
                                    <th class="text-center py-3" style="width: 14.28%;">Friday</th>
                                    <th class="text-center py-3" style="width: 14.28%;">Saturday</th>
                                </tr>
                            </thead>
                            <!-- Calendar Days -->
                            <tbody>
                                @foreach($weeks as $week)
                                    <tr style="height: 120px;">
                                        @foreach($week as $day)
                                            @php
                                                $dayString = $day->format('Y-m-d');
                                                $dayBookings = $bookings[$dayString] ?? collect();
                                                $isCurrentMonth = $day->month == $currentMonth;
                                                $isToday = $dayString === $today;
                                                $isPast = $day->lt(now()->startOfDay());
                                            @endphp
                                            <td class="position-relative p-2 align-top
                                                {{ !$isCurrentMonth ? 'text-muted bg-light' : '' }}
                                                {{ $isToday ? 'bg-primary bg-opacity-10' : '' }}
                                                {{ $isPast ? 'opacity-75' : '' }}">
                                                
                                                <!-- Day Number -->
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <span class="fw-semibold {{ $isToday ? 'text-primary' : '' }}">
                                                        {{ $day->format('j') }}
                                                    </span>
                                                    @if($isToday)
                                                        <span class="badge bg-primary">Today</span>
                                                    @endif
                                                </div>
                                                
                                                <!-- Bookings for this day -->
                                                @if($dayBookings->count() > 0)
                                                    <div class="calendar-events">
                                                        @foreach($dayBookings->take(3) as $booking)
                                                            @php
                                                                $statusColors = [
                                                                    'pending' => 'warning',
                                                                    'approved' => 'success',
                                                                    'rejected' => 'danger',
                                                                    'cancelled' => 'secondary'
                                                                ];
                                                                $color = $statusColors[$booking->status] ?? 'secondary';
                                                            @endphp
                                                            <div class="calendar-event mb-1 cursor-pointer" 
                                                                 wire:click="viewBooking({{ $booking->id }})"
                                                                 title="Click to view details">
                                                                <div class="bg-{{ $color }} bg-opacity-25 border border-{{ $color }} rounded px-2 py-1">
                                                                    <div class="small fw-semibold text-{{ $color == 'warning' ? 'dark' : $color }}" 
                                                                         style="font-size: 0.75rem;">
                                                                        {{ \Carbon\Carbon::parse($booking->time_from)->format('H:i') }}
                                                                    </div>
                                                                    <div class="small text-truncate" 
                                                                         style="font-size: 0.7rem; max-width: 100px;"
                                                                         title="{{ $booking->assetType->name ?? 'N/A' }} - {{ $booking->assetDetail->name ?? 'N/A' }}">
                                                                        {{ $booking->assetType->name ?? 'N/A' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        
                                                        @if($dayBookings->count() > 3)
                                                            <div class="small text-muted">
                                                                +{{ $dayBookings->count() - 3 }} more
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Status Legend</h6>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-25 border border-warning rounded me-2" style="width: 20px; height: 20px;"></div>
                                <span class="small">Pending</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-25 border border-success rounded me-2" style="width: 20px; height: 20px;"></div>
                                <span class="small">Approved</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-25 border border-danger rounded me-2" style="width: 20px; height: 20px;"></div>
                                <span class="small">Rejected</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-25 border border-secondary rounded me-2" style="width: 20px; height: 20px;"></div>
                                <span class="small">Cancelled</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Detail Modal -->
@if($selectedBooking)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Details</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold">Asset Information</h6>
                            <div class="mb-3">
                                <label class="small text-muted">Asset Type</label>
                                <div>{{ $selectedBooking->assetType->name ?? 'N/A' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Asset Detail</label>
                                <div>{{ $selectedBooking->assetDetail->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold">Schedule Information</h6>
                            <div class="mb-3">
                                <label class="small text-muted">Date</label>
                                <div>{{ \Carbon\Carbon::parse($selectedBooking->scheduled_date)->format('F j, Y') }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted">Time</label>
                                <div>
                                    {{ \Carbon\Carbon::parse($selectedBooking->time_from)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($selectedBooking->time_to)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted">Status</label>
                                <div>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger',
                                            'cancelled' => 'secondary'
                                        ];
                                        $color = $statusColors[$selectedBooking->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($selectedBooking->status) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted">Created</label>
                                <div>{{ $selectedBooking->created_at->format('M j, Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($selectedBooking->purpose)
                        <div class="mb-3">
                            <label class="small text-muted">Purpose</label>
                            <div>{{ $selectedBooking->purpose }}</div>
                        </div>
                    @endif
                    
                    @if($selectedBooking->notes)
                        <div class="mb-3">
                            <label class="small text-muted">Notes</label>
                            <div>{{ $selectedBooking->notes }}</div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                    @if($selectedBooking->status === 'pending')
                        <button type="button" class="btn btn-warning">Edit Booking</button>
                        <button type="button" class="btn btn-danger">Cancel Booking</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<style>
    .calendar-event {
        cursor: pointer;
        transition: opacity 0.2s ease;
    }
    
    .calendar-event:hover {
        opacity: 0.8;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    .table td {
        vertical-align: top;
    }
    
    @media (max-width: 768px) {
        .table th, .table td {
            padding: 0.25rem;
            font-size: 0.875rem;
        }
        
        .calendar-event {
            margin-bottom: 0.25rem;
        }
        
        .calendar-event .small {
            font-size: 0.6rem !important;
        }
    }
</style>