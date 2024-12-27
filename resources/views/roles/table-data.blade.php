<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="roles.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('roles.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('roles.index')" :search="$search"></x-search-form>
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
                    route="roles.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    #
                </x-sortable-header>
                
                <x-sortable-header 
                    field="role_name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="roles.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Role Name
                </x-sortable-header>
                
                <th>Description</th>            
                <th>Permissions</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody id="table-data">
            @forelse($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->role_name }}</td>
                <td>
                    {{ strlen($role->description) > 50 ? substr($role->description,0,50)."..." : $role->description; }}
                </td>
                <td class="text-primary text-start">
                    <strong>
                        @foreach ($role->permissions as $permission)
                        <span class="badge rounded-pill bg-primary">{{ $permission->permission_name }}</span>
                        @endforeach
                    </strong>
                </td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>

                        <form hx-post="{{ route('roles.destroy', ['role' => $role->id]) }}" hx-target="body">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="return confirm('Are you sure to delete Role?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center fw-bold">No Records Found...</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div>
        {{ $roles->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>