<div wire:init="loadBookings">
    @if($compactMode)
    <!-- Compact Calendar with FullCalendar -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <h6 class="mb-0">Calendar</h6>
                <div class="d-flex align-items-center gap-2">
                    <button id="prevBtn" class="btn btn-outline-secondary btn-sm px-3">‹</button>
                    <button id="todayBtn" class="btn btn-primary btn-sm px-4">Today</button>
                    <button id="nextBtn" class="btn btn-outline-secondary btn-sm px-3">›</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="calendar-compact" style="height: 400px;"></div>
        </div>
    </div>

    @else
    <!-- Full Calendar with FullCalendar -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h4 class="mb-0">Calendar</h4>
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group" role="group">
                        <button id="dayGridBtn" class="btn btn-outline-primary btn-sm">Month</button>
                        <button id="timeGridWeekBtn" class="btn btn-outline-primary btn-sm">Week</button>
                        <button id="listBtn" class="btn btn-outline-primary btn-sm">List</button>
                    </div>
                    <div class="btn-group" role="group">
                        <button id="prevBtn" class="btn btn-outline-primary btn-sm px-3">‹</button>
                        <button id="todayBtn" class="btn btn-primary btn-sm px-4">Today</button>
                        <button id="nextBtn" class="btn btn-outline-primary btn-sm px-3">›</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="calendar-full" style="min-height: 600px;"></div>
        </div>
    </div>
    @endif

    <!-- Enhanced Modal for Booking Details -->
    @if($selectedBooking)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-check me-2"></i>
                        Booking Details
                    </h5>
                    <button wire:click="closeModal" class="btn-close btn-close-white"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted small">ASSET</label>
                                <p class="mb-0">{{ $selectedBooking->assetDetail->name ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted small">PURPOSE</label>
                                <p class="mb-0">{{ $selectedBooking->purpose ?? 'Not specified' }}</p>
                            </div>
                            @if($selectedBooking->destination)
                            <div class="mb-3">
                                <label class="fw-bold text-muted small">DESTINATION</label>
                                <p class="mb-0">{{ $selectedBooking->destination }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted small">DATE</label>
                                <p class="mb-0">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($selectedBooking->scheduled_date)->format('l, M j, Y') }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted small">TIME</label>
                                <p class="mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $selectedBooking->time_from }} - {{ $selectedBooking->time_to }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted small">STATUS</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $selectedBooking->status === 'approved' ? 'success' : ($selectedBooking->status === 'pending' ? 'warning' : 'danger') }} fs-6">
                                        <i class="fas fa-{{ $selectedBooking->status === 'approved' ? 'check-circle' : ($selectedBooking->status === 'pending' ? 'clock' : 'times-circle') }} me-1"></i>
                                        {{ ucfirst($selectedBooking->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @if($selectedBooking->notes)
                    <div class="mt-3">
                        <label class="fw-bold text-muted small">NOTES</label>
                        <div class="bg-light p-3 rounded">
                            {{ $selectedBooking->notes }}
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @script
    <script>
        // Global calendar instance
        let calendar = null;
        let isCalendarInitialized = false;
        
        function initializeCalendar() {
            // Check if FullCalendar is available
            if (typeof FullCalendar === 'undefined' || typeof FullCalendar.Calendar === 'undefined') {
                console.log('FullCalendar not yet available, waiting...');
                return false;
            }
            
            // Prevent double initialization
            if (isCalendarInitialized) {
                console.log('Calendar already initialized');
                return true;
            }
            
            const calendarEl = document.getElementById('{{ $compactMode ? "calendar-compact" : "calendar-full" }}');
            if (!calendarEl) {
                console.error('Calendar element not found');
                return false;
            }
            
            const bookingsData = @json($bookingsForCalendar);
            console.log('Initializing calendar with bookings:', bookingsData);
            
            try {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: '{{ $compactMode ? "400" : "auto" }}',
                    headerToolbar: false, // We're using custom buttons
                    
                    // Theme and styling
                    themeSystem: 'bootstrap5',
                    
                    // Make sure dates are displayed
                    dayHeaders: true,
                    dayHeaderFormat: { weekday: 'short' },
                    
                    // Events data
                    events: bookingsData,
                    
                    // Calendar options
                    firstDay: 0, // Sunday
                    weekNumbers: false,
                    editable: false,
                    selectable: false,
                    
                    // Ensure proper rendering
                    aspectRatio: {{ $compactMode ? '1.35' : '1.35' }},
                    
                    // Event styling and content
                    eventDidMount: function(info) {
                        // Custom styling based on status
                        const status = info.event.extendedProps.status;
                        const element = info.el;
                        
                        element.style.borderRadius = '6px';
                        element.style.border = 'none';
                        element.style.fontSize = '12px';
                        element.style.fontWeight = '500';
                        
                        if (status === 'approved') {
                            element.style.backgroundColor = '#198754';
                            element.style.color = 'white';
                        } else if (status === 'pending') {
                            element.style.backgroundColor = '#ffc107';
                            element.style.color = '#000';
                        } else {
                            element.style.backgroundColor = '#dc3545';
                            element.style.color = 'white';
                        }
                        
                        // Add hover effect
                        element.addEventListener('mouseenter', function() {
                            element.style.transform = 'scale(1.05)';
                            element.style.transition = 'transform 0.2s ease';
                            element.style.zIndex = '1000';
                        });
                        
                        element.addEventListener('mouseleave', function() {
                            element.style.transform = 'scale(1)';
                            element.style.zIndex = 'auto';
                        });
                    },
                    
                    // Event click handler
                    eventClick: function(info) {
                        const bookingId = info.event.extendedProps.booking_id;
                        $wire.call('viewBooking', bookingId);
                    },
                    
                    // Date click handler
                    dateClick: function(info) {
                        // Optional: Handle date clicks for creating new bookings
                        console.log('Date clicked:', info.dateStr);
                    },
                    
                    // Day cell content
                    dayCellDidMount: function(info) {
                        // Add subtle styling to today's date
                        if (info.date.toDateString() === new Date().toDateString()) {
                            info.el.style.backgroundColor = '#e3f2fd';
                        }
                    },
                    
                    // Responsive design
                    windowResize: function() {
                        calendar.updateSize();
                    },
                    
                    // Debug rendering
                    viewDidMount: function(info) {
                        console.log('Calendar view mounted:', info.view.type);
                    }
                });
                
                calendar.render();
                isCalendarInitialized = true;
                console.log('Calendar initialized and rendered successfully');
                
                // Setup button event listeners
                setupButtonListeners();
                
                return true;
            } catch (error) {
                console.error('Error initializing calendar:', error);
                const calendarEl = document.getElementById('{{ $compactMode ? "calendar-compact" : "calendar-full" }}');
                if (calendarEl) {
                    calendarEl.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error initializing calendar. Please refresh the page.</div>';
                }
                return false;
            }
        }
        
        function setupButtonListeners() {
            // Custom button handlers
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const todayBtn = document.getElementById('todayBtn');
            
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    if (calendar) {
                        calendar.prev();
                        $wire.call('updateCurrentDate', calendar.getDate().toISOString().split('T')[0]);
                    }
                });
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    if (calendar) {
                        calendar.next();
                        $wire.call('updateCurrentDate', calendar.getDate().toISOString().split('T')[0]);
                    }
                });
            }
            
            if (todayBtn) {
                todayBtn.addEventListener('click', function() {
                    if (calendar) {
                        calendar.today();
                        $wire.call('today');
                    }
                });
            }
            
            // View switcher buttons (only for full calendar)
            @if(!$compactMode)
            const dayGridBtn = document.getElementById('dayGridBtn');
            const timeGridWeekBtn = document.getElementById('timeGridWeekBtn');
            const listBtn = document.getElementById('listBtn');
            
            if (dayGridBtn) {
                dayGridBtn.addEventListener('click', function() {
                    if (calendar) {
                        calendar.changeView('dayGridMonth');
                        document.querySelectorAll('#dayGridBtn, #timeGridWeekBtn, #listBtn').forEach(btn => btn.classList.remove('active'));
                        dayGridBtn.classList.add('active');
                    }
                });
            }
            
            if (timeGridWeekBtn) {
                timeGridWeekBtn.addEventListener('click', function() {
                    if (calendar) {
                        calendar.changeView('timeGridWeek');
                        document.querySelectorAll('#dayGridBtn, #timeGridWeekBtn, #listBtn').forEach(btn => btn.classList.remove('active'));
                        timeGridWeekBtn.classList.add('active');
                    }
                });
            }
            
            if (listBtn) {
                listBtn.addEventListener('click', function() {
                    if (calendar) {
                        calendar.changeView('listWeek');
                        document.querySelectorAll('#dayGridBtn, #timeGridWeekBtn, #listBtn').forEach(btn => btn.classList.remove('active'));
                        listBtn.classList.add('active');
                    }
                });
            }
            
            // Set initial active view
            if (dayGridBtn) {
                dayGridBtn.classList.add('active');
            }
            @endif
        }
        
        // Initialize when DOM is ready or FullCalendar becomes available
        document.addEventListener('DOMContentLoaded', function() {
            // Add a small delay to ensure everything is loaded
            setTimeout(function() {
                initializeCalendar();
            }, 100);
        });
        
        // Listen for FullCalendar loaded event (from fallback)
        window.addEventListener('fullcalendar-loaded', function() {
            if (!isCalendarInitialized) {
                setTimeout(function() {
                    initializeCalendar();
                }, 100);
            }
        });
        
        // Livewire refresh handler
        $wire.on('refreshCalendar', () => {
            if (calendar && isCalendarInitialized) {
                const bookingsData = @json($bookingsForCalendar);
                calendar.removeAllEvents();
                calendar.addEventSource(bookingsData);
            }
        });
    </script>
    @endscript

    <style>
        /* Essential FullCalendar CSS - ensures dates are visible */
        .fc {
            font-family: 'Figtree', sans-serif;
            direction: ltr;
            text-align: left;
        }
        
        .fc table {
            border-collapse: collapse;
            border-spacing: 0;
            font-size: 1em;
        }
        
        .fc th,
        .fc td {
            vertical-align: top;
            text-align: left;
        }
        
        /* Day grid essential styles */
        .fc-daygrid {
            position: relative;
        }
        
        .fc-daygrid-day {
            position: relative;
        }
        
        .fc-daygrid-day-number {
            position: relative;
            z-index: 4;
            padding: 4px;
            display: block;
            min-height: 1.5em;
            color: #212529 !important;
            text-decoration: none;
            font-weight: 600 !important;
            font-size: 0.875rem;
        }
        
        .fc-daygrid-day-top {
            position: relative;
            z-index: 2;
        }
        
        /* Column headers */
        .fc-col-header-cell {
            background-color: #f8f9fa !important;
            font-weight: 600 !important;
            color: #495057 !important;
            border: 1px solid #dee2e6 !important;
            padding: 0.75rem 0.5rem !important;
            text-align: center;
        }
        
        /* Day cells */
        .fc-daygrid-day {
            border: 1px solid #dee2e6 !important;
            background: white;
            min-height: 100px;
        }
        
        .fc-day-today {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }
        
        .fc-day-other {
            background-color: #f8f9fa !important;
        }
        
        .fc-day-other .fc-daygrid-day-number {
            color: #6c757d !important;
        }
        
        /* Events */
        .fc-event {
            cursor: pointer;
            transition: all 0.2s ease;
            border: none !important;
            border-radius: 4px;
            margin: 1px;
            padding: 2px 4px;
            font-size: 11px;
        }
        
        .fc-event:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transform: translateY(-1px);
        }
        
        .fc-event-title {
            font-weight: 500;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .fc-daygrid-day {
                min-height: 80px;
            }
            
            .fc-daygrid-day-number {
                font-size: 0.75rem;
                padding: 2px;
            }
            
            .fc-event {
                font-size: 10px;
                padding: 1px 3px;
            }
        }
        
        /* Scrollgrid styling */
        .fc-scrollgrid {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            overflow: hidden;
        }
        
        .fc-scrollgrid-section > * {
            border-left: 0;
            border-right: 0;
        }
        
        .fc-scrollgrid-section-header > td {
            border-bottom: 1px solid #dee2e6;
        }
        
        /* Modal enhancements */
        .modal-content {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .badge.fs-6 {
            font-size: 0.875rem !important;
        }
    </style>
</div>
