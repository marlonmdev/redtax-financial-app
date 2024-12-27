<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="login-history.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('login-history.clear') }}" onclick="return confirm('Are you sure to clear login history?')" hx-target="body" class="btn btn-light" data-bs-toggle="tooltip" title="Clear Login History"><i class="bi bi-trash3-fill"></i></a>
        
        <a hx-get="{{ route('login-history.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        
        <x-search-form :route="route('login-history.index')" :search="$search"></x-search-form>
        <input type="hidden" id="per-page" value="{{ $perPage }}">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover" >
        <thead> 
            <tr>                   
                <x-sortable-header 
                    field="id" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="login-history.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    #
                </x-sortable-header>
                
                <x-sortable-header 
                    field="name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="login-history.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Name
                </x-sortable-header>
                
                <x-sortable-header 
                    field="ip_address" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="login-history.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    IP
                </x-sortable-header>
                
                <x-sortable-header 
                    field="user_agent" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="login-history.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    User Agent
                </x-sortable-header>
                
                <x-sortable-header 
                    field="created_at" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="login-history.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Logged On
                </x-sortable-header>
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($login_histories as $login_history)
                <tr>
                    <td>{{ $login_history->id }}</td>
                    <td>{{ $login_history->name }}</td>
                    <td>{{ $login_history->ip_address }}</td>
                    <td>{{ $login_history->user_agent }}</td>
                    <td>{{ date('M d, Y g:i A', strtotime( $login_history->created_at)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center fw-bold">No Records Found...</td>
                </tr>
            @endforelse
        </tbody>  
    </table>
    <div>
        {{ $login_histories->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>