<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="uploads.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('uploads.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('uploads.index')" :search="$search"></x-search-form>
    </div>
   
</div>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <x-sortable-header 
                field="id" 
                :current-field="request('sort_field')" 
                :current-direction="request('sort_direction')" 
                route="uploads.index" 
                :search="$search"
                :per-page="$perPage"
                >
                Date
            </x-sortable-header>
            
            <x-sortable-header 
                field="name" 
                :current-field="request('sort_field')" 
                :current-direction="request('sort_direction')" 
                route="uploads.index" 
                :search="$search"
                :per-page="$perPage"
                >
                Client Name
            </x-sortable-header>
            
            <x-sortable-header 
                field="email" 
                :current-field="request('sort_field')" 
                :current-direction="request('sort_direction')" 
                route="uploads.index" 
                :search="$search"
                :per-page="$perPage"
                >
                Email
            </x-sortable-header>
            
            <x-sortable-header 
                field="phone" 
                :current-field="request('sort_field')" 
                :current-direction="request('sort_direction')" 
                route="uploads.index" 
                :search="$search"
                :per-page="$perPage"
                >
                Phone
            </x-sortable-header>
            <th class="text-center">Actions</th>
        </thead>
        <tbody id="table-data">
            @forelse($uploads as $upload)
            <tr>
                <td>{{ date('M d, Y g:i A', strtotime($upload->created_at)) }}</td>
                <td>  
                    <div class="d-flex align-items-center gap-2">  
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($upload->name) }}&background=random" alt="user avatar" width="24" height="auto" class="rounded-circle">
                        <span class="link-underline-dark">{{ $upload->name }}</span>
                    </div>
                </td>
                <td>{{ $upload->email }}</td>
                <td>{{ $upload->phone }}</td>
                <td>
                   <div class="d-flex justify-content-end gap-2"> 
                        @if($upload->client_id)
                            <a href="{{ route('clients.show-profile', ['client' => $upload->client_id]) }}" class="btn btn-primary btn-sm" title="View Profile"><i class="bi bi-file-person-fill"></i></a>
                        @endif
                        
                        @if($upload->document_id)
                            <form action="{{ route('documents.download', ['document' => $upload->document_id ]) }}" method="get">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Download Document"><i class='bi bi-download'></i></button>
                            </form>
                        @endif
                            
                        @can('delete', $upload)
                            <form hx-post="{{ route('uploads.destroy', ['upload' => $upload->id]) }}" hx-target="body">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are you sure to delete upload?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
                            </form>
                        @endcan
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
        {{ $uploads->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>