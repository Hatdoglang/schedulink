<div class="px-3 mt-3 text-start">
    <ul class="nav nav-pills bg-light p-1 rounded shadow-sm d-inline-flex" id="approverTabs">
        <li class="nav-item me-1">
            <a class="nav-link d-flex align-items-center fw-semibold px-3 py-2 small
                {{ request()->routeIs('approver.booking-management') ? 'active text-white bg-primary' : 'text-primary' }}"
               href="{{ route('approver.booking-management') }}">
                <i class="bi bi-building me-1"></i>
                Conference Room
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center fw-semibold px-3 py-2 small
                {{ request()->routeIs('approver.vehicle-booking-management') ? 'active text-white bg-success' : 'text-success' }}"
               href="{{ route('approver.vehicle-booking-management') }}">
                <i class="bi bi-truck-front me-1"></i>
                Vehicle
            </a>
        </li>
    </ul>
</div>
