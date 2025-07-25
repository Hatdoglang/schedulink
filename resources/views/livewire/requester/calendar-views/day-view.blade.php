<!-- Day View -->
<div class="day-view">
    <div class="row g-0">
        <!-- Time Column -->
        <div class="col-2 col-md-1">
            <div class="time-column bg-gray-50 border-end">
                <div class="time-header py-3 px-2 border-bottom bg-white">
                    <small class="text-muted fw-medium">Time</small>
                </div>
                @if(isset($timeSlots))
                    @foreach($timeSlots as $timeSlot)
                        <div class="time-slot py-3 px-2 border-bottom d-flex align-items-center" style="height: 80px;">
                            <small class="text-muted">{{ $timeSlot }}</small>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Day Column -->
        <div class="col-10 col-md-11">
            <div class="day-column">
                <!-- Day Header -->
                <div class="day-header py-3 px-4 border-bottom bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-0 fw-semibold">
                                @if(isset($currentDay))
                                    {{ $currentDay->format('l') }}
                                @endif
                            </h6>
                            <div class="d-flex align-items-center mt-1">
                                @if(isset($currentDay))
                                    <span class="day-number {{ $currentDay->format('Y-m-d') === $today ? 'today-number' : 'fw-bold text-dark' }}">
                                        {{ $currentDay->day }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if(isset($currentDay))
                            @php
                                $dateString = $currentDay->format('Y-m-d');
                                $dayBookings = $bookings[$dateString] ?? collect();
                            @endphp
                            @if($dayBookings->count() > 0)
                                <span class="badge bg-primary rounded-pill">{{ $dayBookings->count() }} events</span>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Time Slots with Events -->
                <div class="time-slots-container">
                    @if(isset($timeSlots) && isset($currentDay))
                        @foreach($timeSlots as $timeSlot)
                            @php
                                $dateString = $currentDay->format('Y-m-d');
                                $dayBookings = $bookings[$dateString] ?? collect();
                                $slotBookings = $dayBookings->filter(function($booking) use ($timeSlot) {
                                    return \Carbon\Carbon::parse($booking->time_from)->format('H:i') <= $timeSlot && 
                                           \Carbon\Carbon::parse($booking->time_to)->format('H:i') > $timeSlot;
                                });
                            @endphp
                            <div class="time-slot-row position-relative py-3 px-4 border-bottom" style="min-height: 80px;">
                                @foreach($slotBookings as $booking)
                                    <div class="event-item-day mb-2 p-3 rounded cursor-pointer transition-all" 
                                         wire:click="viewBooking({{ $booking->id }})"
                                         style="background: {{ $booking->status === 'approved' ? 'linear-gradient(135deg, #22c55e, #16a34a)' : ($booking->status === 'pending' ? 'linear-gradient(135deg, #f59e0b, #d97706)' : 'linear-gradient(135deg, #ef4444, #dc2626)') }}; 
                                                color: white; 
                                                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                                border-left: 4px solid rgba(255,255,255,0.5);">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="event-title fw-semibold mb-1">{{ $booking->assetDetail->name ?? 'Booking' }}</div>
                                                <div class="event-time small opacity-90">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $booking->time_from }} - {{ $booking->time_to }}
                                                </div>
                                            </div>
                                            <div class="event-status">
                                                <i class="fas fa-{{ $booking->status === 'approved' ? 'check-circle' : ($booking->status === 'pending' ? 'clock' : 'times-circle') }}"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.day-view .time-column {
    position: sticky;
    left: 0;
    z-index: 10;
}

.day-view .time-slot-row:hover {
    background-color: #f8fafc;
}

.event-item-day {
    max-width: 300px;
}

.event-item-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.day-number {
    font-size: 18px;
    min-width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .event-item-day {
        font-size: 12px;
        padding: 8px !important;
    }
    
    .time-slot-row {
        min-height: 60px !important;
    }
}
</style>