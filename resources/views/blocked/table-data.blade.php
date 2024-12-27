<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="blocked.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('blocked.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('blocked.index')" :search="$search"></x-search-form>
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
                    route="blocked.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Name
                </x-sortable-header>
                
                <x-sortable-header 
                    field="phone" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="blocked.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Phone
                </x-sortable-header>
                
                
                <x-sortable-header 
                    field="email" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="blocked.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Email
                </x-sortable-header>
                
                <x-sortable-header 
                    field="created_at" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="blocked.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Blocked On
                </x-sortable-header>
                
                <th class="text-center">Action</th>
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($bans as $ban)
                <tr>
                    <td>{{ $ban->name }}</td>
                    <td>{{ $ban->phone }}</td>
                    <td>{{ $ban->email }}</td>
                    <td>{{ date('M d, Y g:i A', strtotime($ban->created_at)) }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">                                
                            <form hx-post="{{ route('blocked.unblock', ['blocked' => $ban->id]) }}" hx-target="body">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are you sure to unblock this contact?')" class="btn btn-primary btn-sm" title="Unblock Contact"><i class='bi bi-arrow-clockwise'></i></button>
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
        {{ $bans->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>