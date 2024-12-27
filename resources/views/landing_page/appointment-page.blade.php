<section class="bg-light-red pt-3">
    <div class="container">
        <div class="d-flex justify-content-start align-items-center">
            <h1 class="fs-1 fw-bold text-start text-light-white pb-3 lsp-2">
                <i class="bi bi-calendar2-week"></i> Book an Appointment
            </h1>
        </div>
    </div>
</section>
<section class="bg-dark-red py-1"></section>
<section id="appointment-section" class="bg-light py-5">
    <div class="container">  
        <div class="mb-4">
            <h2 class="text-center">Welcome to our appoinment scheduling page.</h2>
        </div>
        
        <div class="row">
            @forelse($services as $service)
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                                <i class="bi bi-circle-fill text-dark-red me-2"></i>{{ $service->service }}
                            </h4>
                            
                            <div class="d-flex justify-content-start align-items-center gap-2">
                                <i class="bi bi-clock fs-3"></i> <span class="fs-5 fw-medium">{{ $service->formatted_duration }}</span>
                            </div>
                            
                            <p class="card-text">
                                Please follow the instructions to add an event to our calendar. 
                            </p>
                            
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('appointment.calendar', ['service' => $service->id ]) }}" class="btn btn-dark-navy mx-auto">Proceed <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty    
                <h5 class="text-center">No Appointment Services Added Yet...</h5>
            @endforelse
        </div>  
    </div>
</section>
