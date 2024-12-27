<section class="bg-light-red pt-3">
    <div class="container">
        <div class="d-flex justify-content-start align-items-center">
            <h1 class="fs-1 fw-bold text-start text-light-white pb-3 lsp-2">
                <i class="bi bi-phone-vibrate-fill"></i> Contact Us
            </h1>
        </div>
    </div>
</section>
<section class="bg-dark-red py-1"></section>
<section id="document-upload-section" class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <h3 class="fs-2 fw-bold text-start pb-4"><i class="bi bi-chat-right-text-fill"></i> Let's Talk</h3>
                <form hx-post="{{ route('message.submit') }}" hx-target="body">
                    @csrf
                    
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <select class="form-select {{ $errors->has('customer_type') ? 'is-invalid' : '' }}" id="customer-type" name="customer_type">
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
                        
                        <div class="col-lg-8">
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
                                <select class="form-select {{ $errors->has('preferred_contact') ? 'is-invalid' : '' }}" id="preferred-contact" name="preferred_contact">
                                    <option {{ old('preferred_contact') === 'Phone' ? 'selected' : '' }}>Phone</option>
                                    <option {{ old('preferred_contact') === 'Email' ? 'selected' : '' }}>Email</option>
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
                                <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" id="phone" name="phone" placeholder="Phone Number" value="{{ old('phone') }}" oninput="formatPhoneNumber(event)">
                                <label for="phone">Phone Number</label>
                                
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
                    
                    <div class="form-floating mb-3">
                        <select class="form-select {{ $errors->has('subject') ? 'is-invalid' : '' }}" id="subject" name="subject">
                            <option {{ old('subject') === '' ? 'selected' : '' }}>Select a Subject</option>
                            @foreach($serviceTypes as $serviceType)
                                <option value="{{ $serviceType->value }}" {{ old('subject') === $serviceType->value ? 'selected' : '' }}>
                                    {{ $serviceType->value }}
                                </option>
                            @endforeach
                        </select>
                        <label for="subject">*Subject</label>
                        
                        @error('subject')
                            <span class="text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" id="message" name="message" rows="6" placeholder="*Your Message" style="height:180px;">{{ old('message') }}</textarea>
                        <label for="message">*Your Message</label>
                        
                        @error('message')
                            <span class="text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-dark-red p-3">SEND NOW<i class="bi bi-send-check-fill ms-1"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-lg-3">
                <h3 class="fs-2 fw-bold text-start pb-4"><i class="bi bi-info-circle-fill"></i> Get in Touch</h3>
                <div class="d-flex justify-content-start gap-2 mb-2">
                    <div>
                        <i class="bi bi-envelope-at-fill text-dark-red fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark-red">Email Address</h4>
                        <p class="fw-medium">aileen@redtaxservices.com</p>
                    </div>
                </div>
                
                <div class="d-flex justify-content-start gap-2 mb-2">
                    <div>
                        <i class="bi bi-telephone-inbound-fill text-dark-red fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark-red">Call Us</h4>
                        <p class="fw-medium">626-965-8733</p>
                    </div>
                </div>
                
                <div class="d-flex justify-content-start gap-2 mb-2">
                    <div>
                        <i class="bi bi-clock-fill text-dark-red fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark-red">Office Time</h4>
                        <p class="fw-medium">Monday - Friday <br> 9:00am - 5:00pm</p>
                    </div>
                </div>
                
                <div class="d-flex justify-content-start gap-2 mb-2">
                    <div>
                        <i class="bi bi-geo-alt-fill text-dark-red fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark-red">Address</h4>
                        <p class="fw-medium">1414 S. Azusa Ave. Ste. B17, <br> West Covina, CA 91791</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-12">
                <h3 class="fs-2 fw-bold text-start pb-4"><i class="bi bi-pin-map-fill"></i> Where to Find Us</h3>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3305.8130485705387!2d-117.90919812546571!3d34.048666417921154!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c329ea07d112b9%3A0xac1dae2d108c1f79!2s1414%20S%20Azusa%20Ave%20b17%2C%20West%20Covina%2C%20CA%2091791%2C%20USA!5e0!3m2!1sen!2sph!4v1723851116702!5m2!1sen!2sph" 
                    width="100%" 
                    height="480" 
                    style="box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div> 
        </div> 
    </div>
</section>

