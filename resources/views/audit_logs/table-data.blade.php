<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="audit-logs.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">        
        <a hx-get="{{ route('audit-logs.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        
        <x-search-form :route="route('audit-logs.index')" :search="$search"></x-search-form>
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
                    route="audit-logs.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Date
                </x-sortable-header>
                
                <x-sortable-header 
                    field="name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="audit-logs.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Name
                </x-sortable-header>
                
                <x-sortable-header 
                    field="activity" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="audit-logs.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Activity
                </x-sortable-header>                
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($audit_logs as $audit_log)
                <tr>
                    <td>{{ date('M d, Y g:i A', strtotime($audit_log->created_at)) }}</td>
                    <td>{{ $audit_log->name }}</td>
                    <td>{{ $audit_log->activity }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center fw-bold">No Records Found...</td>
                </tr>
            @endforelse
        </tbody>  
    </table>
    <div>
        {{ $audit_logs->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>