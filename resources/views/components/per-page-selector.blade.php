<form id="per-page-form" hx-get="{{ route($route) }}" hx-trigger="change" hx-target="body" 
    hx-vals='{
        "sort_field": "{{ $sortField }}", 
        "sort_direction": "{{ $sortDirection }}", 
        "search": "{{ $search }}"
    }'>
    @csrf
    <select class="form-select" id="per-page" name="per_page">
        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
    </select>
</form>
