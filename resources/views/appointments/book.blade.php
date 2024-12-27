<x-app-layout>
    <div class="pagetitle">
        <h1>Appointments</h1>
        <p class="text-dark"> <a href="{{ route('my-appointments') }}">Appointments</a> <i class="bi bi-caret-right-fill"></i> Booking</p>
    </div>
    <section class="section">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Booking</h5>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <form hx-post="{{ route('appointments.book') }}" hx-target="body">
                                @csrf
                                
                                <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                <input type="hidden" name="phone" value="{{ $client->phone }}">
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select {{ $errors->has('service_id') ? 'is-invalid' : '' }}" id="service-id" name="service_id" onchange="fetchAvailableTimeSlots()">
                                                @if(!empty($services))
                                                    <option value="" {{ old('service_id') === '' ? 'selected' : '' }}>Select Service</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->service }}</option>
                                                    @endforeach                                            
                                                @else
                                                    <option value="" selected>No Services Added Yet</option>
                                                @endif
                                            </select>
                                            <label for="service-id">*Service</label>
                                            
                                            @error('service_id')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select {{ $errors->has('location') ? 'is-invalid' : '' }}" id="location" name="location">
                                                <option value="" {{ old('location') === '' || old('location') === null ? 'selected' : '' }} >Select location</option>
                                                <option value="In-Office" {{ old('location') === 'In-Office' ? 'selected' : '' }} >1414 S. Azusa Ave. Ste. B17, West Covina, CA 91791</option>
                                                <option value="Zoom" {{ old('location') === 'Zoom' ? 'selected' : '' }} >via Zoom</option>
                                            </select>
                                            <label for="location">*Location</label>
                                            
                                            @error('location')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>  
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control {{ $errors->has('date') ? 'is-invalid' : '' }}" id="schedule-date" name="date" placeholder="Date (dd/mm/yyyy)" value="{{ old('date') }}" onchange="fetchAvailableTimeSlots()">
                                            <label for="schedule-date">*Date (dd/mm/yyyy)</label>
                                            
                                            @error('date')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select {{ $errors->has('timeslot') ? 'is-invalid' : '' }}" id="timeslot" name="timeslot" disabled>
                                                <option value="" {{ old('timeslot') === '' || old('timeslot') === null ? 'selected' : '' }} >Select a service and date to generate timeslots</option> 
                                            </select>
                                            
                                            <label for="timeslot">*Available Timeslots</label>
                                            
                                            @error('timeslot')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <textarea class="form-control mb-3 {{ $errors->has('details') ? 'is-invalid' : '' }}" id="details" name="details" rows="6" placeholder="Please share anything that will help prepare for our meeting." style="height:180px;">{{ old('details') }}</textarea>
                                        
                                        @error('details')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div> 
                                    
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control {{ $errors->has('referred_by') ? 'is-invalid' : '' }}" id="referred-by" name="referred_by" placeholder="Referred By" value="{{ old('referred_by') }}">
                                            <label for="referred-by">Referred By</label>
                                            
                                            @error('referred_by')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>   
                                    </div>
                                </div>
                               

                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-dark">Schedule Appointment</button>
                                </div>
                            </form>
                        </div>
                  
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>