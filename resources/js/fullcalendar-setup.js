import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';

window.initFullCalendar = function(elementId, options = {}) {
    const calendarEl = document.getElementById(elementId);
    
    if (!calendarEl) {
        console.error('Calendar element not found:', elementId);
        return null;
    }

    const defaultOptions = {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin, bootstrap5Plugin],
        themeSystem: 'bootstrap5',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day',
            list: 'List'
        },
        height: 'auto',
        aspectRatio: 1.35,
        eventDisplay: 'block',
        displayEventTime: true,
        displayEventEnd: true,
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        dayMaxEvents: 3,
        moreLinkClick: 'popover',
        nowIndicator: true,
        selectable: true,
        selectMirror: true,
        dayMaxEventRows: true,
        weekends: true,
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '08:00',
            endTime: '18:00',
        },
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        allDaySlot: false,
        eventClassNames: function(arg) {
            const status = arg.event.extendedProps.status;
            switch(status) {
                case 'approved':
                    return ['fc-event-approved'];
                case 'pending':
                    return ['fc-event-pending'];
                case 'rejected':
                    return ['fc-event-rejected'];
                default:
                    return ['fc-event-default'];
            }
        },
        eventMouseEnter: function(info) {
            info.el.style.transform = 'scale(1.02)';
            info.el.style.zIndex = '1000';
            info.el.style.transition = 'all 0.2s ease';
        },
        eventMouseLeave: function(info) {
            info.el.style.transform = 'scale(1)';
            info.el.style.zIndex = 'auto';
        },
        eventDidMount: function(info) {
            // Add custom styling based on event status
            const status = info.event.extendedProps.status;
            const element = info.el;
            
            switch(status) {
                case 'approved':
                    element.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
                    element.style.borderColor = '#16a34a';
                    break;
                case 'pending':
                    element.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
                    element.style.borderColor = '#d97706';
                    break;
                case 'rejected':
                    element.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
                    element.style.borderColor = '#dc2626';
                    break;
            }
            
            // Add tooltip
            element.setAttribute('title', `${info.event.title}\nTime: ${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${info.event.end ? info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : ''}\nStatus: ${status || 'Unknown'}`);
        }
    };

    // Merge default options with provided options
    const finalOptions = Object.assign({}, defaultOptions, options);

    const calendar = new Calendar(calendarEl, finalOptions);
    calendar.render();

    return calendar;
};

// Custom CSS for FullCalendar
const style = document.createElement('style');
style.textContent = `
    /* FullCalendar Custom Styles */
    .fc {
        font-family: 'Google Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .fc-toolbar {
        margin-bottom: 1.5rem !important;
    }
    
    .fc-toolbar-title {
        font-size: 1.75rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
    }
    
    .fc-button-group .fc-button {
        border-radius: 0 !important;
        font-weight: 500 !important;
        padding: 8px 16px !important;
        transition: all 0.2s ease !important;
    }
    
    .fc-button-group .fc-button:first-child {
        border-top-left-radius: 6px !important;
        border-bottom-left-radius: 6px !important;
    }
    
    .fc-button-group .fc-button:last-child {
        border-top-right-radius: 6px !important;
        border-bottom-right-radius: 6px !important;
    }
    
    .fc-button-primary {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        border-color: #667eea !important;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3) !important;
    }
    
    .fc-button-primary:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1) !important;
        transform: translateY(-1px) !important;
    }
    
    .fc-button-primary:not(:disabled):active,
    .fc-button-primary:not(:disabled).fc-button-active {
        background: linear-gradient(135deg, #4c51bf, #553c9a) !important;
        border-color: #4c51bf !important;
    }
    
    .fc-today-button {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        border-color: #10b981 !important;
        border-radius: 20px !important;
        margin-right: 10px !important;
    }
    
    .fc-today-button:hover {
        background: linear-gradient(135deg, #059669, #047857) !important;
    }
    
    .fc-daygrid-day-number {
        font-weight: 600 !important;
        color: #374151 !important;
    }
    
    .fc-day-today .fc-daygrid-day-number {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
        color: white !important;
        border-radius: 50% !important;
        width: 28px !important;
        height: 28px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .fc-day-today {
        background-color: #eff6ff !important;
    }
    
    .fc-event {
        border-radius: 6px !important;
        border: none !important;
        padding: 2px 6px !important;
        margin: 1px 0 !important;
        font-size: 11px !important;
        font-weight: 500 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }
    
    .fc-event:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
        transform: translateY(-1px) !important;
    }
    
    .fc-event-title {
        font-weight: 600 !important;
    }
    
    .fc-event-time {
        font-weight: 500 !important;
        opacity: 0.9 !important;
    }
    
    .fc-more-link {
        color: #667eea !important;
        font-weight: 600 !important;
    }
    
    .fc-more-link:hover {
        color: #5a67d8 !important;
    }
    
    .fc-col-header-cell {
        background-color: #f9fafb !important;
        font-weight: 600 !important;
        color: #6b7280 !important;
    }
    
    .fc-daygrid-day {
        transition: background-color 0.2s ease !important;
    }
    
    .fc-daygrid-day:hover {
        background-color: #f8fafc !important;
    }
    
    .fc-timegrid-slot {
        border-top: 1px solid #f3f4f6 !important;
    }
    
    .fc-timegrid-axis {
        background-color: #f9fafb !important;
    }
    
    .fc-scrollgrid {
        border-radius: 8px !important;
        overflow: hidden !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
    }
    
    .fc-theme-bootstrap5 .fc-scrollgrid {
        border: 1px solid #e5e7eb !important;
    }
    
    /* List view styling */
    .fc-list-event {
        transition: background-color 0.2s ease !important;
    }
    
    .fc-list-event:hover {
        background-color: #f8fafc !important;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .fc-toolbar {
            flex-direction: column !important;
            gap: 1rem !important;
        }
        
        .fc-toolbar-chunk {
            display: flex !important;
            justify-content: center !important;
        }
        
        .fc-button-group .fc-button {
            padding: 6px 12px !important;
            font-size: 12px !important;
        }
        
        .fc-event {
            font-size: 10px !important;
        }
    }
    
    /* Animation for calendar loading */
    .fc {
        animation: fadeInUp 0.5s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;

document.head.appendChild(style);