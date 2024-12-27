<section class="bg-light-red pt-3">
    <div class="container">
        <div class="d-flex justify-content-start align-items-center">
            <h1 class="fs-1 fw-bold text-start text-light-white pb-3 lsp-2">
                <i class="bi bi-calendar2-week"></i> Appointment Calendar
            </h1>
        </div>
    </div>
</section>
<section class="bg-dark-red py-1"></section>
<section id="appointment-section" class="bg-light py-5">
    
    <!-- Requirements Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Requirements</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    
                    @include('landing_page.appointment-requirements')
                </div>
            </div>
        </div>
    </div>
        
    
    <div class="container">  
        <div class="d-flex justify-content-start align-items-center gap-3 mb-4">
            <a class="btn btn-dark" href="{{ route('appointment') }}">
                <i class="bi bi-arrow-left"></i> Go Back
            </a>
            
            @if(!str_contains(strtolower($service->service), 'life insurance') && strtolower($service->service) !== 'business consulting')
                <button type="button" class="btn btn-dark-red" data-bs-toggle="modal" data-bs-target="#staticBackdrop" title="Click to show requirements">
                    <i class="bi bi-file-earmark-check-fill me-1"></i>Requirements
                </button>
            @endif
        </div>
        
        <div class="row">
            <div class="col-lg-6 px-2">
                <!-- Service Details -->
                <section>
                    <h4 class="text-dark-red">{{ $service->service }}</h4>
                    <div class="d-flex justify-content-start align-items-center gap-2 text-dark-gray mb-2">
                        <i class="bi bi-clock fs-3"></i> <span class="fs-5 fw-medium">{{ $service->formatted_duration }}</span>
                    </div>
                    
                    @if(str_contains(strtolower($service->service), 'in-office') || str_contains(strtolower($service->service), 'in office'))
                        <div class="d-flex justify-content-start align-items-center gap-2 text-dark-gray mb-2">
                            <i class="bi bi-geo-alt fs-2"></i> <span class="fs-5 fw-medium">1414 S. Azusa Ave Ste B17, West Covina, CA 91791</span>
                        </div>
                    @endif
    
                    @if(str_contains(strtolower($service->service), 'zoom'))
                        <div class="d-flex justify-content-start align-items-start gap-2 text-dark-gray mb-2">
                            <i class="bi bi-camera-video fs-2"></i> <span class="fs-5 fw-medium">Web conferencing details provided upon confirmation.</span>
                        </div>
                    @endif
                </section>
                
                <input type="hidden" id="service-id" value="{{ $service->id }}">
                
                <!-- Calendar Month Navigation -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="mb-3">
                        <div class="d-flex justify-content-end gap-2">
                            <button id="prev-month" class="btn btn-dark-red"><i class="bi bi-arrow-left"></i></button>                    
                            <button id="next-month" class="btn btn-dark-red"><i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>   
                    <div class="mb-3">
                        <h3 id="current-month-year"></h3>
                    </div>
                </div>
                
                <!-- Calendar Table -->
                <div id="calendar"></div>
            </div>
            <div class="col-lg-6"> 
                    
                <div id="timeslot-section" class="p-3"></div>
                
                <div class="text-center">
                    <button id="proceed-btn" class="btn btn-dark d-none">Proceed Now</button>
                </div>
                
                <form action="#" method="post" id="appointment-form" class="d-none" onsubmit="storeAppointment(event)">
                    @csrf
                                  
                    <h4 class="card-title mb-2"><i class="bi bi-info-circle-fill me-1"></i>Enter Your Details</h4>      
                    <h5 class="text-dark-red mb-3">
                        <i class="bi bi-calendar-check-fill me-1"></i> <span id="selected-date-info"></span>
                    </h5>
                    
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <input type="hidden" id="selected-date" name="date">
                    <input type="hidden" id="start-time-input" name="start_time">                    
                    <input type="hidden" id="end-time-input" name="end_time">
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" placeholder="*Name" value="{{ old('name') }}">
                        <label for="name">*Name</label>
                        @error('name')
                            <span class="text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="*Email" value="{{ old('email') }}">
                        <label for="email">*Email</label>
                        
                        @error('email')
                            <span class="text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" id="phone" name="phone" placeholder="*Phone Number" value="{{ old('phone') }}" oninput="formatPhoneNumber(event)">
                        <label for="phone">*Phone Number</label>
                        
                        @error('phone')
                            <span class="text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>
                    
                    @if(str_contains(strtolower($service->service), 'in-office') || str_contains(strtolower($service->service), 'in office'))
                       <input type="hidden" name="location" value="In-Office">
                    @elseif(str_contains(strtolower($service->service), 'zoom'))
                        <input type="hidden" name="location" value="Zoom">
                    @else
                        <div class="form-floating mb-3">
                            <select class="form-select {{ $errors->has('location') ? 'is-invalid' : '' }}" id="location" name="location">
                                <option value="" {{ old('location') === '' || old('location') === null ? 'selected' : '' }} >Select Location</option>
                                <option value="In-Office" {{ old('location') === 'In-Office' ? 'selected' : '' }}>1414 S. Azusa Ave Ste B17, West Covina, CA 91791</option>      
                                <option value="Zoom" {{ old('location') === 'Zoom' ? 'selected' : '' }}>via Zoom</option>                                      
                            </select>
                            <label for="location">*Location</label>
                            
                            @error('location')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>
                    @endif
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control {{ $errors->has('details') ? 'is-invalid' : '' }}" id="details" name="details" rows="6" placeholder="Please share anything that will help prepare for our meeting." style="height:180px;">{{ old('details') }}</textarea>
                        <label for="details">Please share anything that will help prepare for our meeting.</label>
                        
                        @error('details')
                            <span class="text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </span>
                        @enderror
                    </div> 
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control {{ $errors->has('referred_by') ? 'is-invalid' : '' }}" id="referred-by" name="referred_by" placeholder="Referred By" value="{{ old('referred_by') }}">
                        <label for="referred-by">Referred By</label>
                        
                        @error('referred_by')
                            <span class="text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>   
                    
                    <div class="form-group d-flex justify-content-end">
                        <button type="submit" class="btn btn-dark-navy btn-lg">Schedule Event</button>
                    </div>
                </form>
           
            </div>
        </div>
    </div>
</section>
