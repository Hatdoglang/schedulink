<div x-data="calendarComponent()" x-init="initCalendar()">
    @if($compactMode)
    <!-- Compact Calendar Mode -->
    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
        <div class="card-header bg-gradient-primary text-white">
            <h6 class="mb-0 fw-semibold">
                <i class="fas fa-calendar-alt me-2"></i>
                Upcoming Bookings
            </h6>
        </div>
        <div class="card-body p-0">
            <div id="compact-calendar" style="height: 300px;"></div>
        </div>
    </div>

    @else
    <!-- Full Calendar Mode -->
    <div class="calendar-wrapper">
        <!-- Calendar Header -->
        <div class="calendar-header-custom bg-white rounded-lg shadow-sm mb-4 p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3">
                <div>
                    <h2 class="mb-0 fw-bold text-gray-900">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        My Bookings Calendar
                    </h2>
                    <p class="text-muted mb-0 mt-1">Manage and view your asset bookings</p>
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Legend -->
                    <div class="legend d-flex align-items-center gap-3">
                        <div class="legend-item d-flex align-items-center">
                            <div class="legend-color me-2" style="width: 12px; height: 12px; background: #22c55e; border-radius: 50%;"></div>
                            <small class="text-muted">Approved</small>
                        </div>
                        <div class="legend-item d-flex align-items-center">
                            <div class="legend-color me-2" style="width: 12px; height: 12px; background: #f59e0b; border-radius: 50%;"></div>
                            <small class="text-muted">Pending</small>
                        </div>
                        <div class="legend-item d-flex align-items-center">
                            <div class="legend-color me-2" style="width: 12px; height: 12px; background: #ef4444; border-radius: 50%;"></div>
                            <small class="text-muted">Rejected</small>
                        </div>
                    </div>
                    
                    <!-- Refresh Button -->
                    <button @click="refreshCalendar()" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="fas fa-sync-alt me-1"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- FullCalendar Container -->
        <div class="calendar-container bg-white rounded-lg shadow-sm p-4">
            <div id="fullcalendar" style="min-height: 600px;"></div>
        </div>
    </div>
    @endif

    <!-- Enhanced Event Details Modal -->
    @if($selectedBooking)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);" 
         x-show="true" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
                <div class="modal-header border-0 text-white position-relative" 
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle me-3" 
                             style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-calendar-check text-white"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Booking Details</h5>
                            <small class="opacity-90">{{ $selectedBooking->assetDetail->name ?? 'N/A' }}</small>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="btn-close btn-close-white"></button>
                    
                    <!-- Decorative elements -->
                    <div class="position-absolute" style="top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.5;"></div>
                    <div class="position-absolute" style="bottom: -30px; left: -30px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
                </div>
                
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Asset Information -->
                        <div class="col-md-6">
                            <div class="info-card p-3 border rounded-lg bg-light">
                                <label class="info-label text-muted small fw-semibold mb-2">ASSET DETAILS</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-cube text-primary me-2"></i>
                                    <div>
                                        <p class="mb-0 fw-bold">{{ $selectedBooking->assetDetail->name ?? 'N/A' }}</p>
                                        <small class="text-muted">{{ $selectedBooking->assetType->name ?? 'Unknown Type' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date Information -->
                        <div class="col-md-6">
                            <div class="info-card p-3 border rounded-lg bg-light">
                                <label class="info-label text-muted small fw-semibold mb-2">SCHEDULED DATE</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <div>
                                        <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($selectedBooking->scheduled_date)->format('l, M j, Y') }}</p>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($selectedBooking->scheduled_date)->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Time Information -->
                        <div class="col-md-6">
                            <div class="info-card p-3 border rounded-lg bg-light">
                                <label class="info-label text-muted small fw-semibold mb-2">TIME SLOT</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <div>
                                        <p class="mb-0 fw-bold">{{ $selectedBooking->time_from }} - {{ $selectedBooking->time_to }}</p>
                                        <small class="text-muted">
                                            @php
                                                $duration = \Carbon\Carbon::parse($selectedBooking->time_to)->diffInMinutes(\Carbon\Carbon::parse($selectedBooking->time_from));
                                                $hours = floor($duration / 60);
                                                $minutes = $duration % 60;
                                            @endphp
                                            Duration: {{ $hours }}h {{ $minutes }}m
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Information -->
                        <div class="col-md-6">
                            <div class="info-card p-3 border rounded-lg bg-light">
                                <label class="info-label text-muted small fw-semibold mb-2">STATUS</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-{{ $selectedBooking->status === 'approved' ? 'check-circle' : ($selectedBooking->status === 'pending' ? 'clock' : 'times-circle') }} text-primary me-2"></i>
                                    <span class="badge rounded-pill px-3 py-2 fw-medium" 
                                          style="background: {{ $selectedBooking->status === 'approved' ? 'linear-gradient(135deg, #22c55e, #16a34a)' : ($selectedBooking->status === 'pending' ? 'linear-gradient(135deg, #f59e0b, #d97706)' : 'linear-gradient(135deg, #ef4444, #dc2626)') }}; color: white;">
                                        {{ ucfirst($selectedBooking->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Purpose/Description -->
                        @if($selectedBooking->purpose)
                        <div class="col-12">
                            <div class="info-card p-3 border rounded-lg bg-light">
                                <label class="info-label text-muted small fw-semibold mb-2">PURPOSE</label>
                                <p class="mb-0">{{ $selectedBooking->purpose }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="modal-footer border-0 bg-gray-50 d-flex justify-content-between">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Booking ID: #{{ $selectedBooking->id }}
                    </div>
                    <button wire:click="closeModal" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-times me-1"></i>
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function calendarComponent() {
    return {
        calendar: null,
        events: @js($events),
        
        initCalendar() {
            if (typeof window.initFullCalendar === 'undefined') {
                console.error('FullCalendar not loaded yet, retrying...');
                setTimeout(() => this.initCalendar(), 100);
                return;
            }

            const calendarId = @js($compactMode) ? 'compact-calendar' : 'fullcalendar';
            
            const options = {
                initialView: @js($compactMode) ? 'listWeek' : 'dayGridMonth',
                events: this.events,
                eventClick: (info) => {
                    const bookingId = info.event.extendedProps.booking_id;
                    @this.call('viewBooking', bookingId);
                },
                headerToolbar: @js($compactMode) ? false : {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                height: @js($compactMode) ? 300 : 'auto',
                dayMaxEvents: @js($compactMode) ? 2 : 3,
            };

            this.calendar = window.initFullCalendar(calendarId, options);
        },
        
        refreshCalendar() {
            @this.call('refreshEvents').then(() => {
                if (this.calendar) {
                    this.calendar.refetchEvents();
                }
            });
        }
    }
}

// Listen for Livewire events
document.addEventListener('livewire:init', () => {
    Livewire.on('refresh-calendar-events', (events) => {
        // Update calendar events when refreshed from backend
        const calendarEl = document.querySelector('[x-data*="calendarComponent"]');
        if (calendarEl && calendarEl._x_dataStack && calendarEl._x_dataStack[0].calendar) {
            calendarEl._x_dataStack[0].calendar.removeAllEvents();
            calendarEl._x_dataStack[0].calendar.addEventSource(events);
        }
    });
});
</script>

<style>
.calendar-wrapper {
    animation: fadeInUp 0.6s ease-out;
}

.calendar-header-custom {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid rgba(229, 231, 235, 0.5);
}

.info-card {
    transition: all 0.2s ease;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid rgba(229, 231, 235, 0.5);
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.info-label {
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 10px;
    color: #6b7280;
}

.legend-item {
    transition: all 0.2s ease;
}

.legend-item:hover {
    transform: scale(1.05);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom scrollbar for modal */
.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
