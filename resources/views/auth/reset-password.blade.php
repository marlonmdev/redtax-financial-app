<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-floating my-3">
            <input type="email" class="form-control {{ $errors->get('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            <label for="email">Enter your email</label>
            
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
        </div>
        
        <!-- Password -->
        <div class="form-floating my-3">
            <input type="password" class="form-control {{ $errors->get('password') ? 'is-invalid' : '' }}" id="password" name="password" placeholder="Password" required autocomplete="new-password">
            <label for="password">Password</label>
            
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
        </div>
        
         <!-- Confirm Password -->
         <div class="form-floating my-3">
            <input type="password" class="form-control {{ $errors->get('password_confirmation') ? 'is-invalid' : '' }}" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
            <label for="password">Confirm Password</label>
            
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
        </div>
        
        <div class="d-flex justify-content-center mt-2">
            <button type="submit" class="btn btn-dark-red btn-lg">Reset Password</button>
        </div>
    </form>
</x-guest-layout>
