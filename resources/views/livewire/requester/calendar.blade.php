<div>
    @if($compactMode)
    <!-- Simple Compact Calendar -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ $monthName }}</h6>
            <div class="btn-group btn-group-sm">
                <button wire:click="previousMonth" class="btn btn-outline-secondary">‹</button>
                <button wire:click="today" class="btn btn-primary">Today</button>
                <button wire:click="nextMonth" class="btn btn-outline-secondary">›</button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center py-1 small">Sun</th>
                        <th class="text-center py-1 small">Mon</th>
                        <th class="text-center py-1 small">Tue</th>
                        <th class="text-center py-1 small">Wed</th>
                        <th class="text-center py-1 small">Thu</th>
                        <th class="text-center py-1 small">Fri</th>
                        <th class="text-center py-1 small">Sat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weeks as $week)
                    <tr>
                        @foreach($week as $day)
                            @php
                                $dateString = $day->format('Y-m-d');
                                $dayBookings = $bookings[$dateString] ?? collect();
                                $isToday = $dateString === $today;
                                $isCurrentMonth = $day->month === $currentMonth;
                            @endphp
                            <td class="text-center p-1 {{ !$isCurrentMonth ? 'table-secondary' : '' }}" style="height: 50px; width: 14.28%;">
                                <div class="{{ $isToday ? 'bg-primary text-white rounded-circle' : '' }} px-2 py-1">
                                    {{ $day->day }}
                                </div>
                                @if($dayBookings->count() > 0)
                                    <small class="badge bg-success">{{ $dayBookings->count() }}</small>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @else
    <!-- Simple Full Calendar -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ $monthName }}</h4>
            <div class="btn-group">
                <button wire:click="previousMonth" class="btn btn-outline-primary">‹ Previous</button>
                <button wire:click="today" class="btn btn-primary">Today</button>
                <button wire:click="nextMonth" class="btn btn-outline-primary">Next ›</button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center py-3">Sunday</th>
                        <th class="text-center py-3">Monday</th>
                        <th class="text-center py-3">Tuesday</th>
                        <th class="text-center py-3">Wednesday</th>
                        <th class="text-center py-3">Thursday</th>
                        <th class="text-center py-3">Friday</th>
                        <th class="text-center py-3">Saturday</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weeks as $week)
                    <tr>
                        @foreach($week as $day)
                            @php
                                $dateString = $day->format('Y-m-d');
                                $dayBookings = $bookings[$dateString] ?? collect();
                                $isToday = $dateString === $today;
                                $isCurrentMonth = $day->month === $currentMonth;
                            @endphp
                            <td class="align-top p-2 {{ !$isCurrentMonth ? 'table-secondary' : '' }}" style="height: 120px; width: 14.28%;">
                                <div class="d-flex justify-content-between">
                                    <span class="{{ $isToday ? 'bg-primary text-white rounded-circle px-2 py-1' : 'fw-bold' }}">
                                        {{ $day->day }}
                                    </span>
                                </div>
                                @foreach($dayBookings as $booking)
                                    <div class="mt-1">
                                        <small class="badge bg-{{ $booking->status === 'approved' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }} d-block mb-1" 
                                               wire:click="viewBooking({{ $booking->id }})" style="cursor: pointer;">
                                            {{ $booking->time_from }} - {{ $booking->assetDetail->name ?? 'Booking' }}
                                        </small>
                                    </div>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Simple Modal -->
    @if($selectedBooking)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Details</h5>
                    <button wire:click="closeModal" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Asset:</strong> {{ $selectedBooking->assetDetail->name ?? 'N/A' }}</p>
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($selectedBooking->scheduled_date)->format('M j, Y') }}</p>
                    <p><strong>Time:</strong> {{ $selectedBooking->time_from }} - {{ $selectedBooking->time_to }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $selectedBooking->status === 'approved' ? 'success' : ($selectedBooking->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($selectedBooking->status) }}
                        </span>
                    </p>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-secondary">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>