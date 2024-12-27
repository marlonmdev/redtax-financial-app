<x-app-layout>
    <div class="pagetitle">
        <h1>Appointment Management</h1>
        <p class="text-dark"> <a href="{{ route('appointments.index') }}">Appointments</a> <i class="bi bi-caret-right-fill"></i> Edit</p>
    </div>
    <section class="section">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title">Edit Appointment</h5>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <form hx-post="{{ route('appointments.update', ['appointment' => $appointment->id]) }}" hx-target="body">
                                @csrf
                                @method('put')
                                
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <h5 class="text-dark"><i class="bi bi-calendar2-week-fill"></i> Schedule: {{ date('F d, Y', strtotime($appointment->date)).', '. date('g:i A', strtotime($appointment->start_time)) . ' - ' . date('g:i A', strtotime($appointment->end_time)) }} </h5>
                                    
                                    @if($appointment->status !== 'Completed')
                                        <a href="{{ route('appointments.reschedule', ['appointment' => $appointment->id]) }}" class="btn btn-light">Reschedule</a>
                                    @endif
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}" id="status" name="status">
                                                <option value="" {{ old('status', $appointment->status) === '' || old('status', $appointment->status) === null ? 'selected' : '' }} >Select Status</option>
                                                <option value="Pending" {{ old('status', $appointment->status) === 'Pending' ? 'selected' : '' }} >Pending</option>
                                                <option value="Completed" {{ old('status', $appointment->status) === 'Completed' ? 'selected' : '' }} >Completed</option>
                                            </select>
                                            <label for="status">Status</label>
                                            
                                            @error('status')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-8">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" placeholder="Client Name" value="{{ old('name',  $appointment->name) }}">
                                            <label for="name">Client Name</label>
                                            
                                            @error('name')
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
                                            <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Email" value="{{ old('email',  $appointment->email) }}">
                                            <label for="email">Email</label>
                                            
                                            @error('email')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" id="phone" name="phone" placeholder="Phone Number" value="{{ old('phone',  $appointment->phone) }}" oninput="formatPhoneNumber(event)">
                                            <label for="phone">Phone Number</label>
                                            
                                            @error('phone')
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
                                    
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control {{ $errors->has('referred_by') ? 'is-invalid' : '' }}" id="referred-by" name="referred_by" placeholder="Referred By" value="{{ old('referred_by',  $appointment->referred_by) }}">
                                            <label for="referred-by">Referred By</label>
                                            
                                            @error('referred_by')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>    
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control {{ $errors->has('details') ? 'is-invalid' : '' }}" id="details" name="details" rows="6" placeholder="Appointment Details" style="height:140px;">{{ old('details',  $appointment->details) }}</textarea>
                                            <label for="details">Appointment Details</label>
                                            
                                            @error('details')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                                                
                                <div class="form-group d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <a href="{{ route('appointments.index') }}" class="btn btn-dark">Go Back</a>
                                </div>
                            </form>
                        </div>
                  
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>