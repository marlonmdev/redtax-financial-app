<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="block-times.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('block-times.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('block-times.index')" :search="$search"></x-search-form>
        <input type="hidden" id="per-page" value="{{ $perPage }}">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover" >
        <thead> 
            <tr>                                 
                <x-sortable-header 
                    field="block_date" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="block-times.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Block Date
                </x-sortable-header>
                
                <x-sortable-header 
                    field="block_start_time" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="block-times.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Start Time
                </x-sortable-header>
                
                <x-sortable-header 
                    field="block_end_time" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="block-times.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    End Time
                </x-sortable-header>
                
                <x-sortable-header 
                    field="created_at" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="block-times.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Added On
                </x-sortable-header>
                
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($blockTimes as $blockTime)
                <tr>
                    <td>{{ date('F d, Y', strtotime($blockTime->block_date)) }}</td>
                    <td>{{ date('g:i A', strtotime($blockTime->block_start_time)) }}</td>
                    <td>{{ date('g:i A', strtotime($blockTime->block_end_time)) }}</td>
                    <td>{{ date('F d, Y g:i A', strtotime($blockTime->created_at)) }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('block-times.edit', ['blockTime' => $blockTime->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                             
                            <form hx-post="{{ route('block-times.destroy', ['blockTime' => $blockTime->id]) }}" hx-target="body">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are you sure to delete?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
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
        {{ $blockTimes->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>