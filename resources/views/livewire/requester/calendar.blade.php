@if($compactMode)
<!-- Compact Calendar for Dashboard -->
<div class="compact-calendar">
    <!-- Calendar Header -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">{{ $monthName }}</h3>
        <div class="flex space-x-2">
            <button wire:click="previousMonth" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button wire:click="today" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                Today
            </button>
            <button wire:click="nextMonth" class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Compact Calendar Grid -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Days of Week Header -->
        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
            <div class="p-2 text-xs font-medium text-gray-600 text-center">Sun</div>
            <div class="p-2 text-xs font-medium text-gray-600 text-center">Mon</div>
            <div class="p-2 text-xs font-medium text-gray-600 text-center">Tue</div>
            <div class="p-2 text-xs font-medium text-gray-600 text-center">Wed</div>
            <div class="p-2 text-xs font-medium text-gray-600 text-center">Thu</div>
            <div class="p-2 text-xs font-medium text-gray-600 text-center">Fri</div>
            <div class="p-2 text-xs font-medium text-gray-600 text-center">Sat</div>
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7">
            @foreach($weeks as $week)
                @foreach($week as $day)
                    @php
                        $dateString = $day->format('Y-m-d');
                        $dayBookings = $bookings[$dateString] ?? collect();
                        $isToday = $dateString === $today;
                        $isCurrentMonth = $day->month === $currentMonth;
                    @endphp
                    <div class="min-h-[60px] p-1 border-r border-b border-gray-200 {{ !$isCurrentMonth ? 'bg-gray-50' : '' }}">
                        <div class="text-xs {{ $isToday ? 'bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center' : ($isCurrentMonth ? 'text-gray-900' : 'text-gray-400') }}">
                            {{ $day->day }}
                        </div>
                        @if($dayBookings->count() > 0)
                            <div class="mt-1">
                                @foreach($dayBookings->take(2) as $booking)
                                    <div class="text-xs p-1 mb-1 rounded 
                                        @if($booking->status === 'approved') bg-green-100 text-green-800
                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif cursor-pointer"
                                        wire:click="viewBooking({{ $booking->id }})"
                                        title="{{ $booking->assetDetail->name ?? 'N/A' }} - {{ $booking->time_from }} to {{ $booking->time_to }}">
                                        {{ Str::limit($booking->assetDetail->name ?? 'N/A', 10) }}
                                    </div>
                                @endforeach
                                @if($dayBookings->count() > 2)
                                    <div class="text-xs text-gray-500">+{{ $dayBookings->count() - 2 }} more</div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>

@else
<!-- Full Calendar View -->
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
                                                $dateString = $day->format('Y-m-d');
                                                $dayBookings = $bookings[$dateString] ?? collect();
                                                $isToday = $dateString === $today;
                                                $isCurrentMonth = $day->month === $currentMonth;
                                            @endphp
                                            <td class="align-top p-2 {{ !$isCurrentMonth ? 'table-secondary' : '' }}" style="vertical-align: top;">
                                                <!-- Day Number -->
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="fw-bold {{ $isToday ? 'bg-primary text-white rounded-circle px-2 py-1' : ($isCurrentMonth ? 'text-dark' : 'text-muted') }}">
                                                        {{ $day->day }}
                                                    </span>
                                                </div>

                                                <!-- Bookings for this day -->
                                                @if($dayBookings->count() > 0)
                                                    <div class="booking-items">
                                                        @foreach($dayBookings as $booking)
                                                            <div class="mb-1">
                                                                <small class="badge 
                                                                    @if($booking->status === 'approved') bg-success
                                                                    @elseif($booking->status === 'pending') bg-warning
                                                                    @elseif($booking->status === 'rejected') bg-danger
                                                                    @else bg-secondary
                                                                    @endif d-block text-start p-2 cursor-pointer"
                                                                    wire:click="viewBooking({{ $booking->id }})"
                                                                    style="cursor: pointer; font-size: 10px;">
                                                                    <div class="fw-bold">{{ $booking->assetDetail->name ?? 'N/A' }}</div>
                                                                    <div>{{ $booking->time_from }} - {{ $booking->time_to }}</div>
                                                                </small>
                                                            </div>
                                                        @endforeach
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
</div>
@endif

<!-- Booking Details Modal -->
@if($selectedBooking)
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Booking Details</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Asset</label>
                    <p class="text-sm text-gray-900">{{ $selectedBooking->assetDetail->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($selectedBooking->scheduled_date)->format('F j, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Time</label>
                    <p class="text-sm text-gray-900">{{ $selectedBooking->time_from }} - {{ $selectedBooking->time_to }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($selectedBooking->status === 'approved') bg-green-100 text-green-800
                        @elseif($selectedBooking->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($selectedBooking->status === 'rejected') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($selectedBooking->status) }}
                    </span>
                </div>
                @if($selectedBooking->purpose)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Purpose</label>
                    <p class="text-sm text-gray-900">{{ $selectedBooking->purpose }}</p>
                </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end mt-6">
                <button wire:click="closeModal" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Close
                </button>
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