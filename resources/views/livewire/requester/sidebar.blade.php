<!-- resources/views/livewire/requester/sidebar.blade.php -->

<!-- Hamburger toggle (visible on mobile only) -->
<button class="btn btn-outline-secondary d-md-none m-3 position-fixed" type="button" onclick="toggleMobileSidebar()" style="z-index: 1050;">
    <i class="bi bi-list"></i>
</button>

<aside class="sidebar bg-white border-end shadow-sm vh-100 position-fixed top-0 start-0 d-flex flex-column p-0"
    style="width: 250px; z-index: 1040; transition: transform 0.3s ease-in-out;">

    <!-- Branding -->
    <div class="px-4 pt-4 pb-3 border-bottom">
        <h5 class="mb-0 fw-bold text-dark">
            <span class="text-uppercase">Gaisano</span>
            <span class="text-danger">MALLS</span>
        </h5>
        <small class="text-muted">Booking System</small>
    </div>

    <!-- Menu Label -->
    <div class="px-4 pt-3 text-muted fw-semibold small">MENU</div>

    <!-- Navigation -->
    <nav class="flex-grow-1 mt-2">
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a href="{{ route('requester.dashboard') }}"
                    class="nav-link d-flex align-items-center rounded px-3 py-2 {{ request()->routeIs('requester.dashboard') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
                    <i class="bi bi-house-door me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('requester.bookings') }}"
                    class="nav-link d-flex align-items-center rounded px-3 py-2 {{ request()->routeIs('requester.bookings') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
                    <i class="bi bi-calendar-week me-2"></i>
                    Booking Management
                </a>
            </li>


            <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center rounded px-3 py-2 text-dark">
                    <i class="bi bi-person-badge me-2"></i>
                    Client Management
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center rounded px-3 py-2 text-dark">
                    <i class="bi bi-chat-dots me-2"></i>
                    Feedback
                </a>
            </li>
        </ul>
    </nav>
</aside>
