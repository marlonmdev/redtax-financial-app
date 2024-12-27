<x-guest-layout>
    
    @if (session('success'))
        <p class="text-success fw-medium">
            <i class="bi bi-check2-circle fs-4"></i> {{ session('success') }}
        </p>
    @endif

    <form method="POST" action="{{ route('request-access') }}">
        @csrf
       
        <h6 class="mt-3 fw-medium text-justify">*Note: You will be redirected to your dashboard once your request has been granted. Access duration is within 24 hours only.</h6>
        
        <div class="form-floating mt-3 mb-3">
            <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}">
            
            <label for="email">Enter your email address</label>
            
            @error('email')
                <span class="text-danger">
                    <div class="d-flex align-items-end fw-medium">{{ $message }}</div>
                </span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-dark-red btn-lg w-100">REQUEST ACCESS</button>
    </form>
</x-guest-layout>