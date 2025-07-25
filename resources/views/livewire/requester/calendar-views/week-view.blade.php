<!-- Week View -->
<div class="week-view">
    <div class="row g-0">
        <!-- Time Column -->
        <div class="col-1">
            <div class="time-column bg-gray-50 border-end">
                <div class="time-header py-3 px-2 border-bottom bg-white" style="height: 70px;">
                    <small class="text-muted fw-medium">Time</small>
                </div>
                @if(isset($timeSlots))
                    @foreach($timeSlots as $timeSlot)
                        <div class="time-slot py-2 px-1 border-bottom d-flex align-items-center justify-content-center" style="height: 60px;">
                            <small class="text-muted" style="font-size: 10px;">{{ $timeSlot }}</small>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Week Days Columns -->
        <div class="col-11">
            <div class="week-columns">
                <!-- Days Header -->
                <div class="row g-0 border-bottom bg-white">
                    @if(isset($weekDays))
                        @foreach($weekDays as $day)
                            @php
                                $dateString = $day->format('Y-m-d');
                                $dayBookings = $bookings[$dateString] ?? collect();
                                $isToday = $dateString === $today;
                            @endphp
                            <div class="col">
                                <div class="day-header py-3 px-2 border-end text-center" style="height: 70px;">
                                    <div class="day-name small text-muted fw-medium">{{ $day->format('D') }}</div>
                                    <div class="day-number mt-1">
                                        <span class="{{ $isToday ? 'today-number' : 'fw-semibold text-dark' }}">
                                            {{ $day->day }}
                                        </span>
                                    </div>
                                    @if($dayBookings->count() > 0)
                                        <div class="mt-1">
                                            <small class="badge bg-primary rounded-pill" style="font-size: 8px;">{{ $dayBookings->count() }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Time Slots Grid -->
                @if(isset($timeSlots) && isset($weekDays))
                    @foreach($timeSlots as $timeSlot)
                        <div class="row g-0 border-bottom">
                            @foreach($weekDays as $day)
                                @php
                                    $dateString = $day->format('Y-m-d');
                                    $dayBookings = $bookings[$dateString] ?? collect();
                                    $slotBookings = $dayBookings->filter(function($booking) use ($timeSlot) {
                                        return \Carbon\Carbon::parse($booking->time_from)->format('H:i') <= $timeSlot && 
                                               \Carbon\Carbon::parse($booking->time_to)->format('H:i') > $timeSlot;
                                    });
                                @endphp
                                <div class="col">
                                    <div class="time-slot-cell position-relative p-1 border-end" style="height: 60px;">
                                        @foreach($slotBookings as $booking)
                                            <div class="event-item-week mb-1 p-1 rounded cursor-pointer transition-all" 
                                                 wire:click="viewBooking({{ $booking->id }})"
                                                 style="background: {{ $booking->status === 'approved' ? 'linear-gradient(135deg, #22c55e, #16a34a)' : ($booking->status === 'pending' ? 'linear-gradient(135deg, #f59e0b, #d97706)' : 'linear-gradient(135deg, #ef4444, #dc2626)') }}; 
                                                        color: white; 
                                                        font-size: 9px; 
                                                        line-height: 1.2;
                                                        box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                                <div class="event-title text-truncate fw-semibold">{{ $booking->assetDetail->name ?? 'Booking' }}</div>
                                                <div class="event-time text-truncate opacity-90">{{ $booking->time_from }}</div>
                                            </div>
                                        @endforeach
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

<style>
.week-view .time-column {
    position: sticky;
    left: 0;
    z-index: 10;
}

.week-view .time-slot-cell:hover {
    background-color: #f8fafc;
}

.event-item-week:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 6px rgba(0,0,0,0.15) !important;
}

.week-view .day-number {
    font-size: 14px;
    min-width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .week-view .day-header {
        padding: 8px 4px !important;
    }
    
    .week-view .time-slot-cell {
        height: 50px !important;
    }
    
    .event-item-week {
        font-size: 8px !important;
        padding: 2px !important;
    }
    
    .week-view .time-slot {
        height: 50px !important;
    }
}
</style>