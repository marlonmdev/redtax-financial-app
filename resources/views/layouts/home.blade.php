<!doctype html>
<html lang="en" class="h-100" data-bs-theme="auto">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="RED Tax Financial and Insurance Services are a team of seasoned professionals with a passion for helping our clients achieve financial security and success. With years of experience in tax preparation, financial planning, and insurance, we provide comprehensive and personalized solutions tailored to your unique needs. Our commitment to excellence, integrity, and customer service sets us apart. We strive to build lasting relationships based on trust and reliability, ensuring that you receive expert advice and support at every stage of your financial journey. Whether you're an individual, a family, or a business, we are here to guide you with professionalism and care.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? "REDTax Financial Services" }}</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />
    
    @notifyCss
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <link rel="canonical" href="https://redtaxfinancialservices.com">
    <link href="{{ asset('assets/bootstrap-5.3.3/css/bootstrap-zephyr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('redtax-admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    
    <script defer src="{{ asset('assets/htmx/htmx.min.js') }}"></script>
    <script defer src="{{ asset('redtax-admin/js/alpine-3.14.1.min.js') }}"></script>
    <script defer src="{{ asset('assets/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ asset('redtax-admin/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('redtax-admin/js/axios-1.7.3.min.js')}}"></script>
</head>
<body class="snippet-body" hx-indicator="#loader">
    
    <x-notify::notify />
    
    <div id="loader" class="d-flex flex-column justify-content-center align-items-center px-5 py-2">
        <div class="spinner-border my-2" role="status">
            <span class="visually-hidden">Submitting...</span>
        </div>
        <p class="text-center">Submitting...</p>
    </div>
    
    <div class="container-fluid p-0">
        @yield('content')
    </div>
    
    @notifyJs
    <script type="text/javascript" src="{{ asset('assets/aos/aos.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/actions.js') }}"></script>
    <script type="text/javascript">
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
        
        (function() {
            const scrollToTop = () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        })();
                     
        @yield('script')
    </script>
</body>
</html>
