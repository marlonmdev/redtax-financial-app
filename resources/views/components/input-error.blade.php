@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'fw-medium text-danger']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
