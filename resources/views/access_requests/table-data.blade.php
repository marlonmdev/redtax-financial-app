<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="access-requests.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('access-requests.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('access-requests.index')" :search="$search"></x-search-form>
        <input type="hidden" id="per-page" value="{{ $perPage }}">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover" >
        <thead> 
            <tr>                                 
                <x-sortable-header 
                    field="name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="access-requests.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Client Name
                </x-sortable-header>
                <x-sortable-header 
                    field="email" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="access-requests.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Email
                </x-sortable-header>
                
                <x-sortable-header 
                    field="requested_at" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="access-requests.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Requested On
                </x-sortable-header>

                <th class="text-center">Actions</th>
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($accessRequests as $request)
                <tr>
                    <td class="align-middle">
                        <div class="d-flex align-items-center gap-2">  
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($request->name) }}&background=random" alt="user avatar" width="28" height="auto" class="rounded-circle">
                            <span>{{ $request->name }}</span>
                        </div>
                    </td>
                    <td>{{ $request->email }}</td>
                    <td class="text-center">{{ date('F d, Y g:i A', strtotime($request->requested_at)) }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a hx-get="{{ route('access-requests.grant', ['user' => $request->user_id]) }}" hx-target="body" class="btn btn-primary btn-sm" title="Grant Request" onclick="return confirm('Are you sure to grant request?')"><i class="bi bi-hand-thumbs-up-fill"></i></a>
                            <a hx-get="{{ route('access-requests.deny', ['user' => $request->user_id]) }}" hx-target="body" class="btn btn-danger btn-sm" title="Deny Request" onclick="return confirm('Are you sure to deny request?')"><i class="bi bi-hand-thumbs-down-fill"></i></a>
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
        {{ $accessRequests->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>