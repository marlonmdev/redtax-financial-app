<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="appointment-schedules.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('appointment-schedules.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('appointment-schedules.index')" :search="$search"></x-search-form>
        <input type="hidden" id="per-page" value="{{ $perPage }}">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover" >
        <thead> 
            <tr>                                 
                <x-sortable-header 
                    field="day" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="appointment-schedules.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Day
                </x-sortable-header>
                
                <x-sortable-header 
                    field="start_time" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="appointment-schedules.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Start Time
                </x-sortable-header>
                
                <x-sortable-header 
                    field="break_start_time" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="appointment-schedules.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    BreakTime Start
                </x-sortable-header>
                
                <x-sortable-header 
                    field="break_end_time" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="appointment-schedules.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    BreakTime End
                </x-sortable-header>
                
                <x-sortable-header 
                    field="end_time" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="appointment-schedules.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    End Time
                </x-sortable-header>
                
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->day }}</td>
                    <td>{{ date('g:i A', strtotime($schedule->start_time)) }}</td>
                    <td>
                        {{ $schedule->break_start_time ? date('g:i A', strtotime($schedule->break_start_time)) : 'None'  }}
                    </td>
                    <td>
                        {{ $schedule->break_end_time ? date('g:i A', strtotime($schedule->break_end_time)) : 'None' }}
                    </td>
                    <td>{{ date('g:i A', strtotime($schedule->end_time)) }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('appointment-schedules.edit', ['schedule' => $schedule->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                             
                            <form hx-post="{{ route('appointment-schedules.destroy', ['schedule' => $schedule->id]) }}" hx-target="body">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are you sure to delete this schedule?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center fw-bold">No Schedules Found...</td>
                </tr>
            @endforelse
        </tbody>  
    </table>
    <div>
        {{ $schedules->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>