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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <div class="container-fluid vh-100 d-flex flex-column flex-md-row p-0">
        <!-- Left: Login Form -->
        <div class="col-12 col-md-6 d-flex align-items-center justify-content-center p-4">
            <div class="w-100" style="max-width: 400px;">
                {{ $slot }}
            </div>
        </div>

        <!-- Right: Image -->
        <div class="d-none d-md-block col-md-6 p-0" style="margin-top: -320px;">
            <img src="{{ asset('images/signin_or_signup.jpg') }}" alt="Sign In" class="img-fluid h-100 w-100" style="object-fit: cover;">
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts

    {{-- ✅ ADD THIS LINE --}}
    @stack('scripts')
</body>

</html>
