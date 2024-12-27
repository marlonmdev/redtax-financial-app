<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="appointment-services.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('appointment-services.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('appointment-services.index')" :search="$search"></x-search-form>
        <input type="hidden" id="per-page" value="{{ $perPage }}">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover" >
        <thead> 
            <tr>                 
                <x-sortable-header 
                    field="service" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="appointment-services.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Service
                </x-sortable-header>
                
                <x-sortable-header 
                    field="duration" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="appointment-services.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Duration
                </x-sortable-header>
                
                <x-sortable-header 
                    field="id" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="appointment-services.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Added On
                </x-sortable-header>
                
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($services as $service)
                <tr>
                    <td>{{ $service->service }}</td>
                    <td>
                        @php
                            $hours = floor($service->duration / 60);
                            $minutes = $service->duration % 60;
                        @endphp

                        @if ($hours > 0)
                            {{ $hours }} {{ Str::plural('hour', $hours) }} 
                        @endif

                        @if ($minutes > 0)
                            {{ $minutes }} {{ Str::plural('minute', $minutes) }}
                        @endif
                    </td>
                    <td>{{ date('M d, Y g:i A', strtotime($service->created_at)) }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('appointment-services.edit', ['service' => $service->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                             
                            <form hx-post="{{ route('appointment-services.destroy', ['service' => $service->id]) }}" hx-target="body">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are you sure to delete this service?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
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
        {{ $services->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>