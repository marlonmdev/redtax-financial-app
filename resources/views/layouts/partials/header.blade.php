<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'REDTax Financial Services') }}</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />
    
    @notifyCss 
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/bootstrap-5.3.3/css/bootstrap-zephyr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('redtax-admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('redtax-admin/css/style.css') }}" rel="stylesheet">
   
    <script defer src="{{ asset('assets/htmx/htmx.min.js') }}"></script>
    <script defer src="{{ asset('redtax-admin/js/alpine-3.14.1.min.js') }}"></script>
    <script defer src="{{ asset('redtax-admin/js/jquery-3.7.1.min.js') }}"></script>
    <script defer src="{{ asset('redtax-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('redtax-admin/js/axios-1.7.3.min.js')}}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
</head>
<body hx-indicator="#loader">
    
    <div id="loader" class="d-flex flex-column justify-content-center align-items-center px-5 py-2">
        <div class="spinner-border my-2" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-center">Loading...</p>
    </div>
    
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="logo d-flex justify-content-start align-items-center">
            <img src="{{ asset('assets/images/redtax-logo.png') }}" width="160" height="auto" alt="REDTax Logo">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        @if(auth()->user()->avatar === null || auth()->user()->avatar === '')
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="user avatar" class="avatar-img rounded-circle">
                        @else
                            <img src="/storage/{{ auth()->user()->avatar }}" alt="Profile" class="rounded-circle">
                        @endif
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name }}</span>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ auth()->user()->name }}</h6>
                            <span>{{ auth()->user()->role->role_name }} &raquo; {{ auth()->user()->email }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        @if(Gate::allows('accessRestrictedPages'))
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @endif

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" onclick="return confirm('Are you sure to logout?')" class="dropdown-item d-flex align-items-center">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            </ul>
        </nav><!-- End Icons Navigation -->
    </header><!-- End Header -->
