<div>
    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: '/api/calendar-events', // Replace with your actual event source
                selectable: true,
                select: function (info) {
                    alert('Selected from ' + info.startStr + ' to ' + info.endStr);
                },
                eventClick: function (info) {
                    alert('Event: ' + info.event.title);
                },
            });

            calendar.render();
        });
    </script>
</div>
