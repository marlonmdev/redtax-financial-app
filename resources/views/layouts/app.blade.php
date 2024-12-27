@include('layouts.partials.header')

@include('layouts.partials.sidebar')

<main id="main" class="main">
  @if(Gate::allows('isClient') && auth()->user()->agreed_to_terms === 0)
    @include('dashboard.partials.engagement-letter')
  @else
    {{ $slot }}
  @endif
</main>

@include('layouts.partials.footer')

