<th class="sortable-header"
    hx-get="{{ route($route) }}" 
    hx-trigger="click" 
    hx-target="body" 
    hx-vals='{
        "sort_field": "{{ $field }}", 
        "sort_direction": "{{ $currentField == $field && $currentDirection == 'asc' ? 'desc' : 'asc' }}", 
        "search": "{{ $search }}",
        "per_page": "{{ $perPage }}",
        "page": "{{ request('page', 1) }}"
    }'>
    @if($currentField == $field)
        @if($currentDirection == 'asc')
            <i class="bi bi-caret-up-fill fs-6 bi-bold "></i>
        @else
            <i class="bi bi-caret-down-fill fs-6 bi-bold"></i>
        @endif
    @else   
        <i class="bi bi-arrow-down-up fs-6 bi-bold"></i>
    @endif
    {{ $slot }}
</th>
