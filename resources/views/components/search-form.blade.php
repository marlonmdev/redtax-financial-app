@props(['route', 'search'])
<form hx-get="{{ $route }}" hx-target="body" class="form-group d-flex gap-1">
    <input type="text" class="form-control"  id="search-input" name="search" value="{{ $search }}" placeholder="Search for..." />
    <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i></button>
</form>