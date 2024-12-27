<div class="card mb-3">
    <div class="card-body">
        <div class="card-title mb-3">
            <h5>Update Password</h5>
            <small>
                Ensure your account is using a long, random password to stay secure.
            </small>
        </div>
        <form hx-post="{{ route('password.update') }}" hx-target="body" class="col-lg-8 offset-r-4">
            @csrf
            @method('put')
    
            <div class="form-floating mb-3">
                <input type="password" id="current_password" name="current_password" class="form-control {{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }}" placeholder="Current Password">
                <label for="current_password">Current Password</label>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div> 
    
            <div class="form-floating mb-1">
                <input type="password" id="input-password" name="password" class="form-control {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}" placeholder="New Password">
                <label for="input-password">New Password</label>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>
            
            <div class="form-group d-flex justify-content-end gap-1 mb-3">
                <a href="javascript:void(0)" class="fw-medium text-primary mx-4" onclick="generatePassword()" data-bs-toggle="tooltip" title="Generate Random Password"><i class="bi bi-key-fill"></i> Generate Password</a>

                <input class="form-check-input" type="checkbox" onclick="showPassword()" aria-label="Checkbox for showing Password">
                <label>Show Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}" placeholder="Confirm Password">
                <label for="password_confirmation">Confirm Password</label>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
                
            <div class="d-flex justify-content-end align-items-center gap-4">
                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="text-md text-success fw-medium">Saved Successfully</p>
                @endif
                
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form> 
    </div>
</div>