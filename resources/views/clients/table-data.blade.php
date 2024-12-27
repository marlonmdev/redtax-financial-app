<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="clients.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a href="{{ route('export.client-profiles-csv') }}" class="btn btn-success" data-bs-toggle="tooltip" title="Export as CSV"><i class="bi bi-filetype-csv"></i> CSV</a>
        
        <a href="{{ route('export.client-profiles-pdf') }}" class="btn btn-danger" data-bs-toggle="tooltip" title="Export as PDF"><i class="bi bi-filetype-pdf"></i> PDF</a>
        
        <a hx-get="{{ route('clients.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        
        <x-search-form :route="route('clients.index')" :search="$search"></x-search-form>
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
                    route="clients.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Date Added
                </x-sortable-header>
                
                <x-sortable-header 
                    field="name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="clients.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Name
                </x-sortable-header>
                
                <x-sortable-header 
                    field="email" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="clients.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Email
                </x-sortable-header>
                
                <x-sortable-header 
                    field="phone" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="clients.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Phone
                </x-sortable-header>
                <th class="text-center">Access</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody id="table-data">
            @forelse($clients as $client)
            <tr>
                <td>{{ date('m/d/Y', strtotime($client->added_on)) }}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">  
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($client->name) }}&background=random" alt="client avatar" width="30" height="auto" class="rounded-circle">
                        {{ $client->name }}
                    </div>
                </td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->phone }}</td>
                <td class="align-middle">
                    @if ($client->user)                        
                        <div class="d-flex justify-content-center align-items-center">
                            @if($client->user->has_access === 1)
                                <form hx-post="{{ route('users.deactivate', $client->user->id) }}" hx-trigger="click" hx-target="body">
                                    @csrf
                                    @method('PUT')
                                    <button class="form-check form-switch form-switch-sm" title="Click to Deactivate">
                                        <input class="form-check-input" type="checkbox" role="switch" checked>
                                    </button>
                                </form>
                            @else
                                <form hx-post="{{ route('users.activate', $client->user->id) }}" hx-trigger="click" hx-target="body">
                                    @csrf
                                    @method('PUT')
                                    <button class="form-check form-switch form-switch-sm" title="Click to Activate">
                                        <input class="form-check-input" type="checkbox" role="switch">
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <form hx-post="{{ route('clients.create-account', $client->id) }}" hx-target="body">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm" title="Client has no user account yet.">
                                <i class="bi bi-person-plus-fill"></i> Create Account 
                            </button>
                        </form>
                    @endif  
                </td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('clients.show-profile', ['client' => $client->id]) }}" class="btn btn-primary btn-sm" title="View"><i class="bi bi-eye-fill"></i></a>

                        <a href="{{ route('clients.edit', ['client' => $client->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
    
                        @can('delete', $client)
                            <form hx-post="{{ route('clients.destroy', ['client' => $client->id]) }}" hx-target="body">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are you sure to delete Client?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center fw-bold">No Records Found...</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div>
        {{ $clients->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>