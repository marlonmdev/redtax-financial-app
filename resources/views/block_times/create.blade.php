<x-app-layout>
    <div class="pagetitle">
        <h1>Appointment Management</h1>
        <p class="text-dark"> <a href="{{ route('block-times.index') }}">Block Times</a> <i class="bi bi-caret-right-fill"></i> Create</p>
    </div>
    <section class="section">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex justify-content-start align-items-center">
                            <h5 class="card-title">Create Block Time</h5>
                        </div>
                        
                        <div class="col-lg-6 offset-r-6">
                            <span class="badge text-bg-light text-danger fw-bold mb-2">Note: During this period, no appointments can be scheduled.</span>
                            
                            <form hx-post="{{ route('block-times.store') }}" hx-target="body">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control {{ $errors->has('block_date') ? 'is-invalid' : '' }}" id="block-date" name="block_date" placeholder="Date (dd/mm/yyyy)" value="{{ old('block_date') }}">
                                    <label for="block-date">Date (dd/mm/yyyy)</label>
                                    
                                    @error('block_date')
                                        <span class="text-danger">
                                            <p class="text-md fw-medium">{{ $message }}</p>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="time" class="form-control {{ $errors->has('block_start_time') ? 'is-invalid' : '' }}" id="block-start-time" name="block_start_time" placeholder="Start Time" value="{{ old('block_start_time') }}">
                                    <label for="block-start-time">Start Time</label>
                                    
                                    @error('block_start_time')
                                        <span class="text-danger">
                                            <p class="text-md fw-medium">{{ $message }}</p>
                                        </span>
                                    @enderror
                                </div>   

                                <div class="form-floating mb-3">
                                    <input type="time" class="form-control {{ $errors->has('block_end_time') ? 'is-invalid' : '' }}" id="block-end-time" name="block_end_time" placeholder="End Time" value="{{ old('block_end_time') }}">
                                    <label for="block-end-time">End Time</label>
                                    
                                    @error('block_end_time')
                                        <span class="text-danger">
                                            <p class="text-md fw-medium">{{ $message }}</p>
                                        </span>
                                    @enderror
                                </div>      
                                                
                                <div class="form-group d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-success">Save</button>    
                                    <a href="{{ route('block-times.index') }}" class="btn btn-dark">Go Back</a>
                                </div>
                            </form>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>