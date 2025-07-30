<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Schedulink</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/link.png') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- FullCalendar -->
    <link href="https://unpkg.com/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body style="font-family: 'Figtree', sans-serif;" class="bg-light text-dark">
    @include('livewire.approver.layouts.approversidebar')

    <div class="min-vh-100 d-flex flex-column" style="margin-left: 250px;" id="main-content-wrapper">
        @include('livewire.approver.layouts.navigation')

        <div class="bg-white d-md-none d-flex justify-content-between align-items-center border-bottom shadow-sm px-3 py-2">
            <button class="btn btn-outline-dark btn-sm" onclick="toggleMobileSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <span class="fw-semibold">Schedulink</span>
        </div>

        <main class="flex-fill px-3 px-md-4 pt-3" id="main-content">
            {{ $slot }}
        </main>

        @stack('scripts')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/booking-table.js') }}"></script>

    @livewireScripts
</body>

</html>
