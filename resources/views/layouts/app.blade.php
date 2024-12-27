@include('layouts.partials.header')
@include('layouts.partials.sidebar')

<div class="main-panel">
    <div class="main-header">
        <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
                <a href="index.html" class="logo text-danger fw-bold">
                    RedTax Logo
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
            <!-- End Logo Header -->
        </div>
        @include('layouts.partials.navigation')
    </div>

    <div class="container">
        <div class="page-inner">
            @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="alert alert-success bg-success text-white fw-bold" role="alert">
                <i class="fas fa-check fs-5 me-2"></i>{{ session('success') }}
            </div>
            @endif

            @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="alert alert-danger bg-danger text-white fw-bold" role="alert">
                <i class="fas fa-times fs-5 me-2"></i> {{ session('error') }}
            </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>

@include('layouts.partials.footer')