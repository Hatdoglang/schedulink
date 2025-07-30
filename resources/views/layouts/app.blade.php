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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- FullCalendar CSS -->
    <link href="https://unpkg.com/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fullcalendar-custom.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body style="font-family: 'Figtree', sans-serif;" class="bg-light text-dark">
    <!-- Sidebar Include -->
    @include('livewire.requester.sidebar')

    <!-- Main Wrapper -->
    <div class="min-vh-100 d-flex flex-column" style="margin-left: 250px;" id="main-content-wrapper">

        <!-- Mobile Header -->
        <div class="bg-white d-md-none d-flex justify-content-between align-items-center border-bottom shadow-sm px-3 py-2">
            <button class="btn btn-outline-dark btn-sm" onclick="toggleMobileSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <span class="fw-semibold">Schedulink</span>
        </div>

        <!-- Header -->
        <header class="bg-white border-b shadow-sm py-2 px-4 sticky top-0 z-30">
            @include('livewire.requester.header')
        </header>

        <!-- Page Content -->
        <main class="flex-fill px-3 px-md-4 pt-3" id="main-content">
            {{ $slot }}
        </main>
        @stack('scripts')

    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- Alpine Store Initialization -->


    <!-- Load Alpine.js -->
    <script src="https://unpkg.com/alpinejs" defer></script>

    <!-- FullCalendar JS -->
    <script src="https://unpkg.com/fullcalendar@6.1.8/index.global.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/fullcalendar-init.js') }}"></script>

    @livewireScripts
</body>

</html>
