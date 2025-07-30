document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");

    const events = window.calendarEvents.map((event) => ({
        ...event,
        allDay: false,
        start: event.start,
        end: event.end,
    }));

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "timeGridWeek",
        timeZone: "local",
        nowIndicator: true,
        now: new Date().toISOString(),
        height: 650,

        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
        },

        dayMaxEventRows: true,
        views: {
            dayGridMonth: {
                dayMaxEventRows: 2,
            },
        },

        events: events,

        eventDidMount: function (info) {
            const props = info.event.extendedProps;
            let bgColor = "#ecf0f1";
            let borderColor = "#bdc3c7";

            switch (props.timeline_status) {
                case "Ongoing":
                    bgColor = "#d1ecf1"; // light blue
                    borderColor = "#17a2b8"; // blue
                    break;
                case "Ended":
                    bgColor = "#f8d7da"; // light red
                    borderColor = "#dc3545"; // red
                    break;
                case "Incoming":
                    bgColor = "#e2f0d9"; // light green
                    borderColor = "#28a745"; // green
                    break;
            }

            info.el.innerHTML = `
        <div style="font-size: 0.9rem; font-weight: bold;">${
            props.purpose
        }</div>
        <div style="font-size: 0.8rem;">${props.time_range}</div>
        <div style="font-size: 0.75rem;">${props.requested_by}</div>
        <div style="position: absolute; bottom: 4px; right: 6px; font-size: 0.7rem; color: ${borderColor};">
            ${props.timeline_status ?? ""}
        </div>
    `;

            Object.assign(info.el.style, {
                backgroundColor: bgColor,
                border: `2px solid ${borderColor}`,
                borderRadius: "8px",
                padding: "4px 6px",
                color: "#2C3E50",
                display: "flex",
                flexDirection: "column",
                justifyContent: "flex-start",
                height: "100%",
                wordBreak: "break-word",
                whiteSpace: "normal",
                position: "relative",
            });
        },
        eventClick: function (info) {
            const props = info.event.extendedProps;
            const tooltip = `
                <strong>${props.requested_by}</strong><br>
                <b>Purpose:</b> ${props.purpose}<br>
                <b>Asset Type:</b> ${props.asset_type}<br>
                <b>Venue:</b> ${props.venue}<br>
                <b>Status:</b> ${props.status}<br>
                <b>Clock:</b> ${props.timeline_status}
            `;

            Swal.fire({
                title: "Booking Details",
                html: tooltip,
                confirmButtonText: "Close",
            });
        },
    });

    calendar.render();
});
