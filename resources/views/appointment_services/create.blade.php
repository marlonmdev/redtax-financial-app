<x-app-layout>
    <div class="pagetitle">
        <h1>Appointment Management</h1>
        <p class="text-dark"> <a href="{{ route('appointment-services.index') }}">Appointment Services</a> <i class="bi bi-caret-right-fill"></i> Create</p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Create Appointment Service</h5>
                        
                        <form hx-post="{{ route('appointment-services.store') }}" hx-target="body" class="col-lg-8 offset-r-4">
                            @csrf
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control {{ $errors->has('service') ? 'is-invalid' : '' }}" id="service" name="service" placeholder="Service" value="{{ old('service') }}">
                                <label for="service">Service</label>
                                
                                @error('service')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-floating mb-3">
                                <select class="form-select {{ $errors->has('duration') ? 'is-invalid' : '' }}" id="duration" name="duration">
                                    <option value="" {{ old('duration') === '' || old('duration') === null ? 'selected' : '' }} >Select Duration</option>
                                    <option value="30" {{ old('duration') === '30' ? 'selected' : '' }} >30 mins</option>
                                    <option value="60" {{ old('duration') === '60' ? 'selected' : '' }} >1 hour</option>
                                    <option value="90" {{ old('duration') === '90' ? 'selected' : '' }} >1 hour 30 mins</option>
                                    <option value="120" {{ old('duration') === '120' ? 'selected' : '' }} >2 hours</option>
                                    <option value="150" {{ old('duration') === '150' ? 'selected' : '' }} >2 hours 30 mins</option>
                                    <option value="180" {{ old('duration') === '180' ? 'selected' : '' }} >3 hours</option>
                                </select>
                                <label for="duration">Duration</label>
                                
                                @error('duration')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="{{ route('appointment-services.index') }}" class="btn btn-dark">Go Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>