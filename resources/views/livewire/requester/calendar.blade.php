<div>
    <div id="filters">
        <select id="userFilter">
            <option value="">All Users</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
            @endforeach
        </select>

        <select id="assetTypeFilter">
            <option value="">All Asset Types</option>
            @foreach ($assetTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>

        <select id="statusFilter">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                locale: 'en',
                events: function (info, successCallback, failureCallback) {
                    let params = {
                        user_id: document.getElementById('userFilter').value,
                        asset_type_id: document.getElementById('assetTypeFilter').value,
                        status: document.getElementById('statusFilter').value,
                    };

                    fetch('/api/bookings?' + new URLSearchParams(params))
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(failureCallback);
                }
            });

            calendar.render();

            ['userFilter', 'assetTypeFilter', 'statusFilter'].forEach(id => {
                document.getElementById(id).addEventListener('change', () => {
                    calendar.refetchEvents();
                });
            });
        });
    </script>
</div>
