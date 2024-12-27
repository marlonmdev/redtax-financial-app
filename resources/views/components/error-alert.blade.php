@props(['message'])
<div {{ $attributes->merge(['class' => 'alert alert-danger bg-danger text-white fw-medium']) }} role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
    <i class="bi bi-x-circle fs-5 me-2"></i>{{ $message }}
</div>