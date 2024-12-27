<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="users.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('users.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('users.index')" :search="$search"></x-search-form>
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
                    route="users.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Date Added
                </x-sortable-header>
                
                <x-sortable-header 
                    field="name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="users.index" 
                    :search="$search"
                    :per-page="$perPage">
                    Name
                </x-sortable-header>
                
                <x-sortable-header 
                    field="email" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="users.index" 
                    :search="$search"
                    :per-page="$perPage">
                    Email
                </x-sortable-header>
                
                <th class="text-center">Role</th>
                <th class="text-center">Access</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody id="table-data">
            @forelse($users as $user)
            <tr>
                <td>{{ date('m/d/Y g:i A', strtotime($user->created_at)) }}</td>
                <td class="align-middle">
                    <div class="d-flex align-items-center gap-2">  
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="user avatar" width="28" height="auto" class="rounded-circle">
                        <span>{{ $user->name }}</span>
                    </div>
                </td>
                <td class="align-middle">{{ $user->email }}</td>
                <td class="align-middle text-center">
                    <span class="badge rounded-pill bg-primary">{{ $user->role->role_name }}</span> 
                </td>
                <td class="align-middle">
                    <div class="d-flex justify-content-center align-items-center">
                        @if($user->has_access === 1)
                            <form hx-post="{{ route('users.deactivate', $user->id) }}" hx-trigger="click" hx-target="body">
                                @csrf
                                @method('PUT')
                                <button class="form-check form-switch form-switch-sm" title="Click to Deactivate">
                                    <input class="form-check-input" type="checkbox" role="switch" checked>
                                </button>
                            </form>
                        @else
                            <form hx-post="{{ route('users.activate', $user->id) }}" hx-trigger="click" hx-target="body">
                                @csrf
                                @method('PUT')
                                <button class="form-check form-switch form-switch-sm" title="Click to Activate">
                                    <input class="form-check-input" type="checkbox" role="switch">
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        @csrf
                        <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>

                        <form hx-post="{{ route('users.destroy', ['user' => $user->id]) }}" hx-target="body">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="return confirm('Are you sure to delete User?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
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
        {{ $users->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>