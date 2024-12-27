<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center text-success" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-floating mt-3 mb-3">
            <input type="text" class="form-control {{ $errors->get('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" autofocus>
            <label for="email">Enter your email</label>
            
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
        </div>
            

        <!-- Password -->
        <div class="form-floating">
            <input type="password" class="form-control {{ $errors->get('password') ? 'is-invalid' : '' }}" id="password" name="password" placeholder="Enter your password" autocomplete="current-password">
            <label for="email">Enter your password</label>
            
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
        </div>
        
        <!-- Remember Me -->
        {{-- <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
            <label class="form-check-label" for="remember_me">
              Remember Me
            </label>
        </div>  --}}
        
        <div class="d-flex justify-content-end">
            <div class="form-group mb-3 mr-auto">
                <input class="form-check-input" type="checkbox" onclick="showPassword()" aria-label="Checkbox for showing Password">
                <label>Show Password</label>
            </div>
        </div>
                 
        <button type="submit" class="btn btn-dark-red btn-lg w-100" type="submit">LOG IN</button>
        <div class="text-center my-3">
            @if (Route::has('password.request'))
                <a class="underline text-primary fw-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>