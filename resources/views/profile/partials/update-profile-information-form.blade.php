<div class="card mb-3">
    <div class="card-body">
        <div class="card-title">
            <h5>Profile Information</h5>
            <small>
                Update your account's profile information and email address.
            </small>
        </div>
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>
        
        <form hx-post="{{ route('profile.update') }}" hx-target="body" class="col-lg-8 offset-r-4" enctype="multipart/form-data">
            @csrf
            @method('patch')
            
            <div class="mb-3">    
                <div class="d-flex align-items-center gap-4 mb-2">
                    <div>
                        @if(auth()->user()->avatar === null || auth()->user()->avatar === '')
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="user avatar" class="avatar-img rounded-circle">
                        @else
                            <img src="/storage/{{ auth()->user()->avatar }}" alt="Profile" class="rounded-circle img-responsive" width="120">
                        @endif
                    </div>
                    <div>
                        <label>Choose an image for your profile avatar (PNG, JPG)</label>
                        <input type="file" id="avatar" name="avatar" class="form-control p-3 {{ $errors->has('avatar') ? 'is-invalid' : '' }}" accept=".jpg, .jpeg, .png">            
                        @error('avatar')
                            <span class="text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </span>
                        @enderror
                    </div>
                </div>              
            </div>
    
            <div class="form-floating mb-3">
                <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $user->name) }}" placeholder="Name">
                <label for="name">Name</label>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            
            <div class="form-floating mb-3">
                <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email', $user->email) }}" placeholder="Email">
                <label for="email">Email</label>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
                
    
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-md mt-2 text-dark">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-info">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 fw-medium text-sm text-success">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif   
            
            <div class="d-flex justify-content-end align-items-center gap-4">
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="text-md text-success fw-medium">Saved Successfully</p>
                @endif
                
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form> 
    </div>
</div>
