@props(['route', 'search'])
<form action="{{ $route }}" class="form-group d-flex gap-1">
    <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Search for..." />
    <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
</form>