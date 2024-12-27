@props(['message'])

<div {{ $attributes->merge(['class' => 'toast']) }} role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <i class="bi bi-check-circle-fill me-1"></i>
        <strong class="me-auto">Success</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        <p class="text-dark fw-medium">{{ $message }}</p>
    </div>
</div>