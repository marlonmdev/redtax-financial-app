<x-guest-layout>
    <div class="mb-3 text-sm text-dark fw-medium mt-3 text-justify">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-2 text-center text-success" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-floating my-3">
            <input type="email" class="form-control {{ $errors->get('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" autofocus>
            <label for="email">Enter your email</label>
            
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
        </div>

        <div class="d-flex justify-content-center mt-2">
            <button type="submit" class="btn btn-dark-red btn-lg">Email Password Reset Link</button>
        </div>
    </form>
</x-guest-layout>
