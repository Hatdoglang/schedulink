<!-- Month View -->
<div class="month-view">
    @if(isset($weeks))
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
    @endif
</div>