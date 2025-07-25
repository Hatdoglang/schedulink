<!-- Year View -->
<div class="year-view">
    @if(isset($yearMonths))
    <div class="year-grid">
        <div class="row g-3">
            @foreach($yearMonths as $month)
                @php
                    $startOfMonth = $month->copy()->startOfMonth();
                    $endOfMonth = $month->copy()->endOfMonth();
                    $startOfCalendar = $startOfMonth->copy()->startOfWeek();
                    $endOfCalendar = $endOfMonth->copy()->endOfWeek();
                    
                    $weeks = [];
                    $currentWeek = [];
                    $currentDay = $startOfCalendar->copy();

                    while ($currentDay <= $endOfCalendar) {
                        $currentWeek[] = $currentDay->copy();

                        if ($currentDay->dayOfWeek === 6) {
                            $weeks[] = $currentWeek;
                            $currentWeek = [];
                        }

                        $currentDay->addDay();
                    }

                    if (!empty($currentWeek)) {
                        $weeks[] = $currentWeek;
                    }
                @endphp
                
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="mini-month-calendar bg-white border rounded shadow-sm">
                        <!-- Mini Month Header -->
                        <div class="mini-month-header text-center py-2 bg-gray-50 border-bottom">
                            <h6 class="mb-0 fw-semibold text-gray-800">{{ $month->format('F') }}</h6>
                        </div>
                        
                        <!-- Mini Month Body -->
                        <div class="mini-month-body p-1">
                            <!-- Mini Days Header -->
                            <div class="row g-0 mb-1">
                                @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $dayLetter)
                                    <div class="col">
                                        <div class="mini-day-header text-center py-1">
                                            <small class="text-muted fw-medium" style="font-size: 9px;">{{ $dayLetter }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Mini Calendar Grid -->
                            @foreach($weeks as $week)
                                <div class="row g-0">
                                    @foreach($week as $day)
                                        @php
                                            $dateString = $day->format('Y-m-d');
                                            $dayBookings = $bookings[$dateString] ?? collect();
                                            $isToday = $dateString === $today;
                                            $isCurrentMonth = $day->month === $month->month;
                                        @endphp
                                        <div class="col">
                                            <div class="mini-calendar-day position-relative text-center {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }}"
                                                 style="height: 24px; cursor: pointer;"
                                                 wire:click="setViewFormat('day')"
                                                 title="{{ $day->format('F j, Y') }}{{ $dayBookings->count() > 0 ? ' - ' . $dayBookings->count() . ' events' : '' }}">
                                                <span class="mini-day-number {{ $isToday ? 'today-number-mini' : '' }} {{ !$isCurrentMonth ? 'text-muted' : 'text-dark' }}"
                                                      style="font-size: 10px; line-height: 24px;">
                                                    {{ $day->day }}
                                                </span>
                                                @if($dayBookings->count() > 0)
                                                    <div class="mini-event-indicator position-absolute" 
                                                         style="bottom: 1px; left: 50%; transform: translateX(-50%);">
                                                        <div class="mini-dot bg-primary rounded-circle" 
                                                             style="width: 4px; height: 4px;"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Mini Month Footer with Event Count -->
                        @php
                            $monthBookings = $bookings->filter(function($dayBookings, $date) use ($month) {
                                return \Carbon\Carbon::parse($date)->month === $month->month && 
                                       \Carbon\Carbon::parse($date)->year === $month->year;
                            })->flatten();
                        @endphp
                        @if($monthBookings->count() > 0)
                            <div class="mini-month-footer text-center py-1 border-top bg-gray-50">
                                <small class="text-muted">{{ $monthBookings->count() }} events</small>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
.year-view .mini-month-calendar {
    transition: all 0.2s ease;
}

.year-view .mini-month-calendar:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.year-view .mini-calendar-day {
    transition: all 0.2s ease;
}

.year-view .mini-calendar-day:hover {
    background-color: #f1f5f9;
}

.year-view .mini-calendar-day.today {
    background-color: #eff6ff;
}

.year-view .mini-calendar-day.other-month {
    opacity: 0.4;
}

.today-number-mini {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white !important;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-top: 4px;
}

.mini-event-indicator .mini-dot {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

@media (max-width: 768px) {
    .year-view .mini-month-calendar {
        margin-bottom: 1rem;
    }
    
    .year-view .mini-calendar-day {
        height: 20px !important;
    }
    
    .year-view .mini-day-number {
        font-size: 8px !important;
        line-height: 20px !important;
    }
}
</style>