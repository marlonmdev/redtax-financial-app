<x-app-layout>
    <div class="pagetitle">
        <h1>Appointment Management</h1>
        <p class="text-dark"> <a href="{{ route('appointments.index') }}">Appointments</a> <i class="bi bi-caret-right-fill"></i> Reschedule</p>
    </div>
    <section class="section">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Reschedule Appointment</h5>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <form hx-post="{{ route('appointments.schedule-update', ['appointment' => $appointment->id]) }}" hx-target="body">
                                @csrf
                                @method('put')
                                
                                <input type="hidden" id="start-time-input" name="start_time">                    
                                <input type="hidden" id="end-time-input" name="end_time">
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="text-dark"><i class="bi bi-person-fill"></i> Current Client: {{ $appointment->name }} </h5>
                                    <h5 class="text-dark"><i class="bi bi-calendar2-week-fill"></i> Schedule: {{ date('F d, Y', strtotime($appointment->date)).', '. date('g:i A', strtotime($appointment->start_time)) . ' - ' . date('g:i A', strtotime($appointment->end_time)) }} </h5>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select {{ $errors->has('service_id') ? 'is-invalid' : '' }}" id="service-id" name="service_id" onchange="fetchTimeSlots()">
                                                @if(!empty($services))
                                                    <option value="" {{ old('service_id', $appointment->service_id) === '' ? 'selected' : '' }}>Select Service</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>{{ $service->service }}</option>
                                                    @endforeach                                            
                                                @else
                                                    <option value="" selected>No Services Added Yet</option>
                                                @endif
                                            </select>
                                            <label for="service-id">Service</label>
                                            
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
                                                <option value="" {{ old('location', $appointment->location) === '' || old('location', $appointment->location) === null ? 'selected' : '' }} >Select location</option>
                                                <option value="In-Office" {{ old('location', $appointment->location) === 'In-Office' ? 'selected' : '' }} >1414 S. Azusa Ave. Ste. B17, West Covina, CA 91791</option>
                                                <option value="Zoom" {{ old('location', $appointment->location) === 'Zoom' ? 'selected' : '' }} >via Zoom</option>
                                            </select>
                                            <label for="location">Location</label>
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
                                            <input type="date" class="form-control {{ $errors->has('date') ? 'is-invalid' : '' }}" id="reschedule-date" name="date" placeholder="Date (dd/mm/yyyy)" value="{{ old('date',  $appointment->date) }}" onchange="fetchTimeSlots()">
                                            <label for="reschedule-date">Date (dd/mm/yyyy)</label>
                                            
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
                                                <option value="" {{ old('timeslot') === '' || old('timeslot') === null ? 'selected' : '' }} >Select a date to generate timeslots</option> 
                                            </select>
                                            
                                            <label for="timeslot">Available Timeslots</label>
                                            
                                            @error('timeslot')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <a href="javascript:window.history.back();" class="btn btn-dark">Go Back</a>
                                </div>
                            </form>
                        </div>
                  
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>