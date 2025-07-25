<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Schedulink</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/link.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/main.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- Custom Calendar Enhancements -->
    <link href="{{ asset('css/calendar-enhancements.css') }}" rel="stylesheet">

    <!-- Custom Styles (Optional if using Vite for your app.css) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body style="font-family: 'Figtree', sans-serif;" class="bg-light text-dark">
    <div class="min-vh-100 d-flex flex-column">

        {{-- Optional Navigation --}}
        {{-- <livewire:layout.navigation /> --}}

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow-sm border-bottom py-3">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-fill">
            {{ $slot }}
        </main>

        <!-- Optional Footer -->
        {{-- <footer class="bg-white border-top py-3 text-center">
            <small class="text-muted">&copy; {{ date('Y') }} Schedulink</small>
        </footer> --}}
    </div>
    
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/main.min.js" crossorigin="anonymous"></script>
    
    <!-- Bootstrap 5 JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
    <!-- Verify FullCalendar loaded -->
    <script>
        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            // Check if FullCalendar is loaded
            if (typeof FullCalendar === 'undefined') {
                console.error('FullCalendar failed to load from CDN');
                
                // Show a user-friendly message
                const calendars = document.querySelectorAll('[id*="calendar"]');
                calendars.forEach(function(cal) {
                    cal.innerHTML = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>Calendar library is loading... Please refresh the page if this message persists.</div>';
                });
                
                // Try to load fallback version
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/fullcalendar@6.1.18/main.min.js';
                script.onload = function() {
                    console.log('FullCalendar fallback loaded successfully');
                    location.reload(); // Reload to initialize calendar
                };
                script.onerror = function() {
                    console.error('FullCalendar fallback also failed to load');
                };
                document.head.appendChild(script);
            } else {
                console.log('FullCalendar loaded successfully', FullCalendar.Calendar);
            }
        });
        
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            if (sidebar) sidebar.classList.toggle('collapsed');
            if (mainContent) mainContent.classList.toggle('expanded');
        }

        // Mobile sidebar toggle
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) sidebar.classList.toggle('show');
        }
    </script>
    
    @livewireScripts
</body>

</html>
