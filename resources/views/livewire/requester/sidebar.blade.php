<aside class="position-fixed top-0 start-0 vh-100 bg-white border-end shadow-sm d-flex flex-column" style="width: 15vw; z-index: 1050;">
    <!-- Sidebar Header -->
    <div class="px-4 py-4 bg-primary text-white">
        <h1 class="h5 mb-0 fw-bold">MyApp</h1>
        <small class="text-light">Requester Panel</small>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow-1 overflow-auto mt-3">
        <ul class="nav flex-column px-3">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('requester.dashboard') }}"
                   class="nav-link d-flex align-items-center rounded {{ request()->routeIs('requester.dashboard') ? 'active text-primary fw-semibold bg-light' : 'text-dark' }}">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                              d="M3 12l2-2m0 0l7-7 7 7m-9 5v6m-4 0h8"/>
                    </svg>
                    Dashboard
                </a>
            </li>

            <!-- Requests -->
            <li class="nav-item">
                <a href="#"
                   class="nav-link d-flex align-items-center text-dark rounded hover-bg-light">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2z"/>
                    </svg>
                    Requests
                </a>
            </li>

            <!-- Settings -->
            <li class="nav-item">
                <a href="#"
                   class="nav-link d-flex align-items-center text-dark rounded hover-bg-light">
                    <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                              d="M11 11V9m0 0V7m0 2h2m-2 2h2m-2 2v2m0-2H9m2 0h2M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Settings
                </a>
            </li>
        </ul>
    </nav>
</aside>
