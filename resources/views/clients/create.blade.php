<x-app-layout>
    <div class="pagetitle">
        <h1>Client Management</h1>
        <p class="text-dark"> <a href="{{ route('clients.index') }}">Client Profiles</a> <i class="bi bi-caret-right-fill"></i> Create</p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Create Client</h5>
                        
                        <form hx-post="{{ route('clients.store') }}" hx-target="body">
                            @csrf
                            
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-floating mb-3">
                                        <select class="form-select {{ $errors->has('customer_type') ? 'is-invalid' : '' }}" id="customer-type" name="customer_type" onchange="toggleCompanyField()">
                                            <option value="" {{ old('customer_type') === '' ? 'selected' : '' }}>Select Customer Type</option>
                                            <option value="Individual" {{ old('customer_type') === 'Individual' ? 'selected' : '' }}>Individual</option>
                                            <option value="Business" {{ old('customer_type') === 'Business' ? 'selected' : '' }}>Business</option>
                                        </select>
                                        <label for="customer-type">*Customer Type</label>
                                        
                                        @error('customer_type')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div  class="col-lg-7">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" id="company" name="company" placeholder="Business/Company" value="{{ old('company') }}" {{ old('customer_type') !== 'Business' ? 'disabled' : '' }}>
                                        <label for="company">Business/Company</label>
                                        
                                        @error('company')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div  class="col-lg-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" placeholder="*Full Name" value="{{ old('name') }}">
                                        <label for="name">*Full Name</label>
                                        @error('name')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>    
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="preferred-contact" name="preferred_contact">
                                          <option value="Phone" selected>Phone</option>
                                          <option value="Email">Email</option>
                                        </select>
                                        <label for="preferred-contact">*Prefer contact via</label>
                                    </div>
                                    @error('preferred_contact')
                                        <span class="text-danger">
                                            <p class="text-md fw-medium">{{ $message }}</p>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" id="phone" name="phone" placeholder="*Phone Number" value="{{ old('phone') }}" oninput="formatPhoneNumber(event)">
                                        <label for="phone">*Phone Number</label>
                                        @error('phone')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-lg-5">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="*Email" value="{{ old('email') }}">
                                        <label for="email">*Email</label>
                                        @error('email')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>  
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" id="address" name="address" placeholder="*Address" value="{{ old('address') }}">
                                        <label for="address">*Address</label>
                                        @error('address')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" id="city" name="city" placeholder="*City" value="{{ old('city') }}">
                                        <label for="city">*City</label>
                                        @error('city')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" id="state" name="state" placeholder="*State" value="{{ old('state') }}">
                                        <label for="state">*State</label>
                                        @error('state')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-lg-2">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control {{ $errors->has('zip_code') ? 'is-invalid' : '' }}" id="zip_code" name="zip_code" placeholder="*Zip Code" value="{{ old('zip_code') }}">
                                        <label for="zip_code">*Zip Code</label>
                                        @error('city')
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
                                        <input type="text" class="form-control {{ $errors->has('tax_identification_number') ? 'is-invalid' : '' }}" id="tax-identification-number" name="tax_identification_number" placeholder="Tax Identification Number" value="{{ old('tax_identification_number') }}">
                                        <label for="tax-identification-number">Tax Identification Number</label>
                                        @error('tax_identification_number')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
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
                            

                            <div class="form-group d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="{{ route('clients.index') }}" class="btn btn-dark">Go Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>