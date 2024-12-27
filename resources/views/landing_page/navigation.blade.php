<header class="container-fluid sticky-top bg-light-white">
  <nav  class="container navbar navbar-expand-lg py-3">
      <div class="container-fluid d-flex justify-content-evenly align-items-center">
          <a class="navbar-brand" href="{{ route('home') }}">
              <img src="{{ asset('assets/images/redtax-logo.png') }}" width="120" height="auto" class="img-responsive" alt="REDTax Financial Services Logo">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse text-center" id="navbarScroll">
            
            <ul class="navbar-nav me-auto my-2 my-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">ABOUT US</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('services') }}">SERVICES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->segment(1) === 'contact' ? 'active' : '' }}" href="{{ route('contact') }}">CONTACT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->segment(1) === 'appointment' ? 'active' : '' }}" href="{{ route('appointment') }}">BOOK AN APPOINTMENT</a>
                </li>
            </ul>
            
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('document-upload') }}" class="btn btn-md btn-dark-red p-3">DOCUMENT UPLOAD<i class="bi bi-upload ms-2"></i></a>
                {{-- <a href="{{ route('login') }}" class="btn btn-md btn-dark-navy p-3">LOGIN<i class="bi bi-box-arrow-in-right ms-2"></i></a> --}}
                                
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-md btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                      LOGIN AS
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-person-fill-lock fs-5"></i> EMPLOYEE</a></li>
                      <li><a class="dropdown-item" href="{{ route('client-login') }}"><i class="bi bi-person-fill-lock fs-5"></i> CLIENT</a></li>
                    </ul>
                </div>
            </div>
      </div>
  </nav>    
</header>