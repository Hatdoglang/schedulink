<div x-data="{ showEventDetail: false, selectedEvent: null }">
    @if($compactMode)
    <!-- Compact Calendar -->
    <div class="calendar-container shadow-sm border-0 rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="calendar-header bg-white border-bottom px-4 py-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <h6 class="mb-0 fw-semibold text-gray-800">{{ $monthName }}</h6>
                <div class="calendar-nav d-flex align-items-center gap-1">
                    <button wire:click="previousMonth" class="nav-btn btn btn-light border-0 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-chevron-left text-muted"></i>
                    </button>
                    <button wire:click="today" class="btn btn-primary px-3 py-2 rounded-pill fw-medium">Today</button>
                    <button wire:click="nextMonth" class="nav-btn btn btn-light border-0 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-chevron-right text-muted"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <!-- Days Header -->
            <div class="calendar-days-header bg-gray-50 border-bottom">
                <div class="row g-0">
                    @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                        <div class="col">
                            <div class="day-header text-center py-3 text-muted fw-medium small">{{ $day }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Calendar Body -->
            <div class="calendar-body">
                @foreach($weeks as $weekIndex => $week)
                <div class="row g-0">
                    @foreach($week as $day)
                        @php
                            $dateString = $day->format('Y-m-d');
                            $dayBookings = $bookings[$dateString] ?? collect();
                            $isToday = $dateString === $today;
                            $isCurrentMonth = $day->month === $currentMonth;
                        @endphp
                        <div class="col">
                            <div class="calendar-day position-relative {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }}" 
                                 style="height: 60px; border-right: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                                <div class="day-number position-absolute top-0 start-0 p-2">
                                    <span class="day-text {{ $isToday ? 'today-number' : '' }} {{ !$isCurrentMonth ? 'text-muted' : '' }}">
                                        {{ $day->day }}
                                    </span>
                                </div>
                                @if($dayBookings->count() > 0)
                                    <div class="events-indicator position-absolute" style="bottom: 4px; left: 50%; transform: translateX(-50%);">
                                        <span class="badge bg-primary rounded-pill px-2" style="font-size: 10px;">{{ $dayBookings->count() }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @else
    <!-- Full Calendar -->
    <div class="calendar-container shadow-sm border-0 rounded-lg overflow-hidden bg-white">
        <!-- Header -->
        <div class="calendar-header bg-white border-bottom px-4 py-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h3 class="mb-0 fw-bold text-gray-900">{{ $monthName }}</h3>

                <div class="calendar-nav d-flex align-items-center gap-1">
                    <button wire:click="previousMonth" class="nav-btn btn btn-light border-0 rounded-circle p-2 d-flex align-items-center justify-content-center transition-all" style="width: 40px; height: 40px;">
                        <i class="fas fa-chevron-left text-muted"></i>
                    </button>
                    <button wire:click="today" class="btn btn-primary px-4 py-2 rounded-pill fw-medium mx-2">Today</button>
                    <button wire:click="nextMonth" class="nav-btn btn btn-light border-0 rounded-circle p-2 d-flex align-items-center justify-content-center transition-all" style="width: 40px; height: 40px;">
                        <i class="fas fa-chevron-right text-muted"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <!-- Days Header -->
            <div class="calendar-days-header bg-gray-50 border-bottom">
                <div class="row g-0">
                    @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                        <div class="col">
                            <div class="day-header text-center py-3 text-muted fw-semibold">{{ $day }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Calendar Body -->
            <div class="calendar-body">
                @foreach($weeks as $weekIndex => $week)
                <div class="row g-0">
                    @foreach($week as $day)
                        @php
                            $dateString = $day->format('Y-m-d');
                            $dayBookings = $bookings[$dateString] ?? collect();
                            $isToday = $dateString === $today;
                            $isCurrentMonth = $day->month === $currentMonth;
                        @endphp
                        <div class="col">
                            <div class="calendar-day position-relative {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }}" 
                                 style="height: 140px; border-right: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                                <!-- Day Number -->
                                <div class="day-number p-3">
                                    <span class="day-text {{ $isToday ? 'today-number' : '' }} {{ !$isCurrentMonth ? 'text-muted' : 'fw-semibold' }}">
                                        {{ $day->day }}
                                    </span>
                                </div>
                                
                                <!-- Events -->
                                <div class="events-container px-2 pb-2" style="max-height: 100px; overflow-y: auto;">
                                    @foreach($dayBookings->take(3) as $booking)
                                        <div class="event-item mb-1 p-2 rounded cursor-pointer transition-all" 
                                             wire:click="viewBooking({{ $booking->id }})"
                                             style="background: {{ $booking->status === 'approved' ? 'linear-gradient(135deg, #22c55e, #16a34a)' : ($booking->status === 'pending' ? 'linear-gradient(135deg, #f59e0b, #d97706)' : 'linear-gradient(135deg, #ef4444, #dc2626)') }}; 
                                                    color: white; 
                                                    font-size: 11px; 
                                                    line-height: 1.2;
                                                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                            <div class="event-time fw-semibold">{{ $booking->time_from }}</div>
                                            <div class="event-title text-truncate">{{ $booking->assetDetail->name ?? 'Booking' }}</div>
                                        </div>
                                    @endforeach
                                    
                                    @if($dayBookings->count() > 3)
                                        <div class="more-events text-muted small mt-1 px-2">
                                            +{{ $dayBookings->count() - 3 }} more
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Enhanced Modal -->
    @if($selectedBooking)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);" x-show="true" x-transition>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
                <div class="modal-header border-0 bg-gradient-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title fw-bold">Event Details</h5>
                    <button wire:click="closeModal" class="btn-close btn-close-white" style="filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-circle me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $selectedBooking->assetDetail->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">Asset Booking</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted small fw-semibold">DATE</label>
                                <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($selectedBooking->scheduled_date)->format('l, M j, Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="text-muted small fw-semibold">TIME</label>
                                <p class="mb-0 fw-medium">{{ $selectedBooking->time_from }} - {{ $selectedBooking->time_to }}</p>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="info-item">
                                <label class="text-muted small fw-semibold">STATUS</label>
                                <div class="mt-1">
                                    <span class="badge rounded-pill px-3 py-2 fw-medium" 
                                          style="background: {{ $selectedBooking->status === 'approved' ? 'linear-gradient(135deg, #22c55e, #16a34a)' : ($selectedBooking->status === 'pending' ? 'linear-gradient(135deg, #f59e0b, #d97706)' : 'linear-gradient(135deg, #ef4444, #dc2626)') }}; color: white;">
                                        <i class="fas fa-{{ $selectedBooking->status === 'approved' ? 'check-circle' : ($selectedBooking->status === 'pending' ? 'clock' : 'times-circle') }} me-1"></i>
                                        {{ ucfirst($selectedBooking->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-gray-50">
                    <button wire:click="closeModal" class="btn btn-outline-secondary rounded-pill px-4">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* Google Calendar Inspired Styles */
.calendar-container {
    font-family: 'Google Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.calendar-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.nav-btn:hover {
    background-color: #e2e8f0 !important;
    transform: scale(1.05);
}

.calendar-day {
    transition: all 0.2s ease;
    cursor: pointer;
}

.calendar-day:hover {
    background-color: #f8fafc;
}

.calendar-day.today {
    background-color: #eff6ff;
}

.calendar-day.other-month {
    background-color: #f9fafb;
}

.day-text {
    font-size: 14px;
    font-weight: 500;
}

.today-number {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white !important;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.event-item {
    cursor: pointer;
    transition: all 0.2s ease;
}

.event-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
}

.bg-gray-50 {
    background-color: #f9fafb;
}

.text-gray-800 {
    color: #1f2937;
}

.text-gray-900 {
    color: #111827;
}

.transition-all {
    transition: all 0.2s ease;
}

.info-item label {
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 11px;
}

/* Scrollbar Styling */
.events-container::-webkit-scrollbar {
    width: 3px;
}

.events-container::-webkit-scrollbar-track {
    background: transparent;
}

.events-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.events-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Animation for modal */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 40px, 0);
    }
    to {
        opacity: 1;
        transform: none;
    }
}

.modal-content {
    animation: fadeInUp 0.3s ease-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .calendar-day {
        height: 100px !important;
    }
    
    .day-header {
        font-size: 12px;
        padding: 8px !important;
    }
    
    .event-item {
        font-size: 10px !important;
        padding: 4px 6px !important;
    }
}
</style>
