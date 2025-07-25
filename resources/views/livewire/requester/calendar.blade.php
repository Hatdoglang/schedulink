<div>
    @if($compactMode)
    <!-- Compact Calendar -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <h6 class="mb-0">{{ $monthName }}</h6>
                <div class="d-flex align-items-center gap-2">
                    <button wire:click="previousMonth" class="btn btn-outline-secondary btn-sm px-3">‹</button>
                    <button wire:click="today" class="btn btn-primary btn-sm px-4">Today</button>
                    <button wire:click="nextMonth" class="btn btn-outline-secondary btn-sm px-3">›</button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <th class="text-center py-1 small">{{ $day }}</th>
                        @endforeach
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
                            <td class="text-center p-1 {{ !$isCurrentMonth ? 'table-secondary' : '' }}" style="height: 50px;">
                                <div class="{{ $isToday ? 'bg-primary text-white rounded-circle d-inline-block px-2 py-1' : '' }}">
                                    {{ $day->day }}
                                </div>
                                @if($dayBookings->count() > 0)
                                    <div class="mt-1">
                                        <small class="badge bg-success">{{ $dayBookings->count() }}</small>
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

    @else
    <!-- Full Calendar -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h4 class="mb-0">{{ $monthName }}</h4>

                <div class="d-flex align-items-center gap-2">
                    <button wire:click="previousMonth" class="btn btn-outline-primary btn-sm px-3">‹</button>
                    <button wire:click="today" class="btn btn-primary btn-sm px-4">Today</button>
                    <button wire:click="nextMonth" class="btn btn-outline-primary btn-sm px-3">›</button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <th class="text-center py-2">{{ $day }}</th>
                        @endforeach
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
                            <td class="align-top p-2 {{ !$isCurrentMonth ? 'table-secondary' : '' }}" style="height: 120px;">
                                <div class="d-flex justify-content-between">
                                    <span class="{{ $isToday ? 'bg-primary text-white rounded-circle px-2 py-1' : 'fw-bold' }}">
                                        {{ $day->day }}
                                    </span>
                                </div>
                                @foreach($dayBookings as $booking)
                                    <div class="mt-1">
                                        <small class="badge bg-{{ $booking->status === 'approved' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }} d-block text-truncate mb-1"
                                               wire:click="viewBooking({{ $booking->id }})"
                                               style="cursor: pointer; max-width: 100%;">
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

    <!-- Modal -->
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
