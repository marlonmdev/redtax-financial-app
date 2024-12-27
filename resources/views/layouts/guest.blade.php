<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'REDTax Financial Services') }}</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <link href="{{ asset('assets/bootstrap-5.3.3/css/bootstrap-zephyr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('redtax-admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/guest.css') }}" rel="stylesheet">
    
    <script defer src="{{ asset('assets/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ asset('assets/htmx/htmx.min.js') }}"></script>
    
    <!-- Scripts -->
    {{-- @vite(['resources/js/app.js'])   --}}
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/images/redtax-logo.png') }}" width="200" height="auto" class="img-responsive" alt="RED Tax Financial Services Logo">
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>
    </main>
    <script src="{{ asset('assets/js/custom.js') }}"></script> 
</body>
</html>