<div>
    <div class="container py-4">
        <div id="calendar"></div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

   <script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const events = @json($events); // Laravel injects your PHP array here

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            events: events,
            eventClick: function (info) {
                const props = info.event.extendedProps;
                alert(
                    `Purpose: ${info.event.title}\n` +
                    `Requested by: ${props.requested_by}\n` +
                    `Asset Type: ${props.asset_type}\n` +
                    `Venue: ${props.venue}\n` +
                    `Status: ${props.status}`
                );
            },
        });

        calendar.render();
    });
</script>

</div>
