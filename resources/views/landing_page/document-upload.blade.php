<section class="bg-light-red pt-3">
    <div class="container">
        <div class="d-flex justify-content-start align-items-center">
            <h1 class="fs-1 fw-bold text-start text-light-white pb-3 lsp-2">
                <i class="bi bi-cloud-arrow-up-fill"></i> Secured Document Upload</h1>
        </div>
    </div>
</section>
<section class=" bg-dark-red py-1"></section>
<section class="bg-light-gray py-5">
    <div class="container">
        <h1 class="section-heading text-center text-dark-navy mb-3"><i class="bi bi-dash-lg"></i> Document Upload <i class="bi bi-dash-lg"></i></h1>
        <h3 class="fs-6 fw-medium text-justify lh-base pb-4">Our document upload feature allows you to securely and conveniently submit your financial documents online. This ensures a streamlined process for tax preparation, financial planning, and insurance services, enabling us to serve you more efficiently. Your privacy and data security are our top priorities, providing you with peace of mind as you share your important documents with us.</h3>
        <div class="row">
            <div class="col-12">    
                    
                <form hx-post="{{ route('document-upload.submit') }}" hx-target="body" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <div class="form-floating">
                                <select class="form-select {{ $errors->has('customer_type') ? 'is-invalid' : '' }}" id="customer-type" name="customer_type" onchange="toggleCompanyField()">
                                    <option value="" {{ old('customer_type') === '' ? 'selected' : '' }}>Select Customer Type</option>
                                    <option value="Individual" {{ old('customer_type') === 'Individual' ? 'selected' : '' }}>Individual</option>
                                    <option value="Business" {{ old('customer_type') === 'Business' ? 'selected' : '' }}>Business</option>
                                </select>
                                <label for="customer-type">*Customer Type</label>
                            </div>
                            
                            @error('customer_type')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>
                                                
                        <div class="col-lg-5 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" placeholder="*Full Name" value="{{ old('name') }}">
                                <label for="name">*Full Name</label>
                            </div>
                            
                            @error('name')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>
                                               
                        <div class="col-lg-4 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" id="company" name="company" placeholder="Business/Company" value="{{ old('company') }}" {{ old('customer_type') !== 'Business' ? 'disabled' : '' }}>
                                <label for="company">Business/Company</label>
                            </div>
                            
                            @error('company')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <div class="form-floating">
                                <select class="form-select {{ $errors->has('preferred_contact') ? 'is-invalid' : '' }}" id="preferred_contact" name="preferred_contact">
                                    <option {{ old('preferred_contact') === 'Phone' ? 'selected' : '' }}>Phone</option>
                                    <option {{ old('preferred_contact') === 'Email' ? 'selected' : '' }}>Email</option>
                                </select>
                                <label for="services">*Prefer contact via</label>
                            </div>
                            
                            @error('preferred_contact')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-lg-4 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" id="phone" name="phone" placeholder="*Phone Number" value="{{ old('phone') }}" oninput="formatPhoneNumber(event)">
                                <label for="phone">*Phone Number</label>
                            </div>
                            @error('phone')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-lg-5 mb-3">
                            <div class="form-floating">
                                <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="*Email" value="{{ old('email') }}">
                                <label for="email">*Email</label>
                            </div>
                            
                            @error('email')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                            
                            <div class="text-danger text-md fw-medium" id="emailError"></div>
                        </div>
                    </div>
                    
                    <div class="row"> 
                       <div class="col-lg-4 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" id="address" name="address" placeholder="*Address" value="{{ old('address') }}">
                                <label for="address">*Address</label>
                            </div>
                           
                             
                            @error('address')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                       </div>
                       
                       <div class="col-lg-3 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" id="city" name="city" placeholder="*City" value="{{ old('city') }}">
                                <label for="city">*City</label>
                            </div>
                            
                            @error('city')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-lg-3 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" id="state" name="state" placeholder="*State" value="{{ old('state') }}">
                                <label for="city">*State</label>
                            </div>
                            
                            @error('state')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="col-lg-2 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control {{ $errors->has('zip_code') ? 'is-invalid' : '' }}" id="zip-code" name="zip_code" placeholder="*Zip Code" value="{{ old('zip_code') }}">
                                <label for="zip-code">*Zip Code</label>
                            </div>
                            
                            @error('zip_code')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>    
                    </div>
                 
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-floating mb-3">
                                <select class="form-select {{ $errors->has('services') ? 'is-invalid' : '' }}" id="services" name="services">
                                    <option {{ old('services') === '' ? 'selected' : '' }}>Select a Service</option>
                                    @foreach($serviceTypes as $serviceType)
                                        <option value="{{ $serviceType->value }}" {{ old('services') === $serviceType->value ? 'selected' : '' }}>
                                            {{ $serviceType->value }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="services">*Services</label>
                                
                                @error('services')
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
                        </div>
                                            
                        <div class="col-lg-7">
                            <div class="form-floating mb-3">
                                <textarea class="form-control {{ $errors->has('details') ? 'is-invalid' : '' }}" id="details" name="details" rows="6" placeholder="More Details..." style="height:180px;">{{ old('details') }}</textarea>
                                <label for="details">More Details...</label>
                                
                                @error('details')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>          
                         
                    <div class="form-group mb-4">
                        <label class="pb-1">*Documents to Upload </label><span class="badge text-bg-secondary text-danger ms-1">JPG, PDF, Doc, Excel - multiple files are automatically zip upon submission</span>
                        <input type="file" id="document" name="document[]" class="form-control px-3 py-3 {{ $errors->has('document') || $errors->has('document.*') ? 'is-invalid' : '' }}" accept=".jpg, jpeg, .csv, .pdf, .doc, .docx, .xls, .xlsx" multiple>
                        
                        @error('document')
                            <span class="text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </span>
                        @enderror
                        
                        @error('document.*')
                            @foreach ($errors->get('document.*') as $messages)
                                @foreach ($messages as $message)
                                    <span class="text-danger text-md fw-medium me-1">
                                        {{ $message }}
                                    </span>
                                @endforeach
                            @endforeach
                        @enderror
                    </div>
                     
                    <div class="d-flex justify-content-end align-items-center gap-2">
                        <button type="submit" class="btn btn-dark-red p-3">SUBMIT NOW</button>
                    </div>
                </form> 
            </div>
        </div>        
    </div>
</section>