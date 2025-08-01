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

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- FullCalendar CSS -->
    <link href="https://unpkg.com/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

    <!-- Custom App CSS/JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<style>
    .notyf__toast {
        background-color: #333 !important;
        color: #fff !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2) !important;
    }
</style>

<body style="font-family: 'Figtree', sans-serif;" class="bg-light text-dark">

    <!-- Sidebar -->
    @include('livewire.admin-staff.layouts.staffsidebar')

    <!-- Main Content Wrapper -->
    <div class="min-vh-100 d-flex flex-column" style="margin-left: 250px;" id="main-content-wrapper">

        <!-- Top Navigation -->
        @include('livewire.admin-staff.layouts.navigation')

        <!-- Mobile Header (for responsive sidebar toggle) -->
        <div class="bg-white d-md-none d-flex justify-content-between align-items-center border-bottom shadow-sm px-3 py-2">
            <button class="btn btn-outline-dark btn-sm" onclick="toggleMobileSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <span class="fw-semibold">Schedulink</span>
        </div>

        <!-- Page Content -->
        <main class="flex-fill px-3 px-md-4 pt-3" id="main-content">
            <div class="px-4 pt-3">
                <livewire:requester.notification.notification-header />
            </div>
            {{ $slot }}
        </main>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/admin-staff.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <!-- Custom Scripts -->
    @stack('scripts')

    <!-- Livewire Scripts (must come after everything else) -->
    @livewireScripts
</body>

</html>
