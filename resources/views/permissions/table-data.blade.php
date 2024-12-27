<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="permissions.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('permissions.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('permissions.index')" :search="$search"></x-search-form>
        <input type="hidden" id="per-page" value="{{ $perPage }}">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <x-sortable-header 
                    field="id" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="permissions.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    #
                </x-sortable-header>
                
                <x-sortable-header 
                    field="permission_name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="permissions.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Permission Name
                </x-sortable-header>
                                
                <th>Description</th>     
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody id="table-data">
            @forelse($permissions as $permission)
            <tr>
                <td>{{ $permission->id }}</td>
                <td>{{ $permission->permission_name }}</td>
                <td>
                    {{ strlen($permission->description) > 40 ? substr($permission->description,0,40)."..." : $permission->description; }}
                </td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('permissions.edit', ['permission' => $permission->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>

                        <form hx-post="{{ route('permissions.destroy', ['permission' => $permission->id]) }}" hx-target="body">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="return confirm('Are you sure to delete Permission?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center fw-bold">No Records Found...</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div>
        {{ $permissions->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>