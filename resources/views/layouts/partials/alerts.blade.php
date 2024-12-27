@if (session()->has('success'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="alert alert-success bg-success text-white fw-medium" role="alert">
        <i class="bi bi-check-lg me-1"></i>{{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="alert alert-danger bg-danger text-white fw-medium" role="alert">
        <i class="bi bi-exclamation-lg me-1"></i> {{ session('error') }}
    </div>
@endif