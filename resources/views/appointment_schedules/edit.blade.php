<x-app-layout>
    <div class="pagetitle">
        <h1>Appointment Management</h1>
        <p class="text-dark"> <a href="{{ route('appointment-schedules.index') }}">Appointment Schedules</a> <i class="bi bi-caret-right-fill"></i> Edit</p>
    </div>
    <section class="section">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <!-- Schedule Time -->
                    <div class="row">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Edit Schedules</h5>
                            <a href="{{ route('appointment-schedules.index') }}" class="btn btn-dark">Close</a>
                        </div>
                        
                        <div class="mb-3">
                            <form hx-post="{{ route('appointment-schedules.update') }}" hx-target="body">
                                @csrf
                                @method('put')
                                
                                <div class="d-flex gap-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="allDaysCheckbox" onchange="checkAllDays(this)">
                                        <label class="form-check-label" for="allDaysCheckbox">
                                            All Days
                                        </label>
                                    </div>
                                    
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        <div class="form-check">
                                            <input type="checkbox"
                                                class="form-check-input day-checkbox {{ $errors->has('days') ? 'is-invalid' : '' }}" 
                                                name="days[]" 
                                                value="{{ $day }}" 
                                                id="{{ strtolower($day) }}Checkbox" 
                                                onchange="updateAllDaysCheckbox()"
                                                {{ in_array($day, old('days', [$schedule->day])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ strtolower($day) }}Checkbox">
                                                {{ $day }}
                                            </label>
                                        </div>  
                                    @endforeach
                                </div>
                                
                                @error('days')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="time" class="form-control {{ $errors->has('start_time') ? 'is-invalid' : '' }}" id="start-time" name="start_time" placeholder="Start Time" value="{{ old('start_time', $schedule->start_time) }}">
                                            <label for="start-time">Start Time</label>
                                            
                                            @error('start_time')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="time" class="form-control {{ $errors->has('end_time') ? 'is-invalid' : '' }}" id="end-time" name="end_time" placeholder="End Time" value="{{ old('end_time', $schedule->end_time) }}">
                                            <label for="end-time">End Time</label>
                                            
                                            @error('end_time')
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
                                            <input type="time" class="form-control {{ $errors->has('break_start_time') ? 'is-invalid' : '' }}" id="break-start-time" name="break_start_time" placeholder="Break Start Time" value="{{ old('break_start_time', $schedule->break_start_time) }}">
                                            <label for="break-start-time">Break Time Start</label>
                                            
                                            @error('break_start_time')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="time" class="form-control {{ $errors->has('break_end_time') ? 'is-invalid' : '' }}" id="break-end-time" name="break_end_time" placeholder="Break End Time" value="{{ old('break_end_time', $schedule->break_end_time) }}">
                                            <label for="break-end-time">Break Time End</label>
                                            
                                            @error('break_end_time')
                                                <span class="text-danger">
                                                    <p class="text-md fw-medium">{{ $message }}</p>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>                                                                                          
                                
                                <div class="form-group d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-success">Update Schedules</button>
                                    <button type="button" class="btn btn-dark" onclick="resetTimeInputs()">Reset Break Time</button>
                                </div>
                            </form>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>