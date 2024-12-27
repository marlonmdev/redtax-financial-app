@php
    $routeLink = '';
    if($schedule == 'this-week'){
        $routeLink = 'appointments.index';
    }
    elseif($schedule == 'previous'){
        $routeLink = 'appointments.previous';
    }
    elseif($schedule == 'upcoming'){
        $routeLink = 'appointments.upcoming';
    }
    elseif($schedule == 'completed'){
        $routeLink = 'appointments.completed';
    }
    elseif($schedule == 'client-only'){
        $routeLink = 'my-appointments';
    }
@endphp

<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        :route="$routeLink" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">   
        
        @if(Gate::allows('accessRestrictedPages'))
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-md btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-filetype-pdf"></i> PDF
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('export.appointments-pdf', ['status' => 'All']) }}" data-bs-toggle="tooltip" title="Export All Appointments as PDF">All</a></li>
                    <li><a class="dropdown-item" href="{{ route('export.appointments-pdf', ['status' => 'Pending']) }}" data-bs-toggle="tooltip" title="Export Pending Appointments as PDF">Pending</a></li>
                    <li><a class="dropdown-item" href="{{ route('export.appointments-pdf', ['status' => 'Completed']) }}" data-bs-toggle="tooltip" title="Export Completed Appointments as PDF">Completed</a></li>
                </ul>
            </div>
        
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-md btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-filetype-csv"></i> CSV
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('export.appointments-csv', ['status' => 'All']) }}" data-bs-toggle="tooltip" title="Export All Appointments as CSV">All</a></li>
                    <li><a class="dropdown-item" href="{{ route('export.appointments-csv', ['status' => 'Pending']) }}" data-bs-toggle="tooltip" title="Export Pending Appointments as CSV">Pending</a></li>
                    <li><a class="dropdown-item" href="{{ route('export.appointments-csv', ['status' => 'Completed']) }}" data-bs-toggle="tooltip" title="Export Completed Appointments as CSV">Completed</a></li>
                </ul>
            </div>
        @endif
        
        <a hx-get="{{ route($routeLink) }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
            
        <x-search-form :route="route($routeLink)" :search="$search"></x-search-form>
        
        <input type="hidden" id="per-page" value="{{ $perPage }}">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover" >
        <thead> 
            <tr>                              
                <x-sortable-header 
                    field="date" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    :route="$routeLink" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Date
                </x-sortable-header>
                
                <x-sortable-header 
                    field="name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    :route="$routeLink" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Client Name
                </x-sortable-header>
                                
                <th>Service</th>        
                
                <x-sortable-header 
                    field="start_time" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    :route="$routeLink" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Time
                </x-sortable-header>
                
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ date('D - M d, Y', strtotime($appointment->date)) }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">  
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($appointment->name) }}&background=random" alt="client avatar" width="30" height="auto" class="rounded-circle">
                            {{ $appointment->name }}
                        </div>
                    </td>
                    <td>{{ $appointment->service->service }}</td>
                    <td>{{ date('g:i A', strtotime($appointment->start_time)) }} - {{ date('g:i A', strtotime($appointment->end_time)) }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            @if(Gate::allows('accessRestrictedPages'))
                                <a href="{{ route('appointments.show', ['appointment' => $appointment->id]) }}" class="btn btn-primary btn-sm" title="View Appointment"><i class="bi bi-eye-fill"></i></a>
                            @else
                                <form action="{{ route('my-appointments.preview') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $appointment->id }}">
                                    <button type="submit" class="btn btn-primary btn-sm" title="View Appointment"><i class='"bi bi-eye-fill'></i></button>
                                </form>
                            @endif
                            
                            @can('update', $appointment)
                                <a href="{{ route('appointments.edit', ['appointment' => $appointment->id]) }}" class="btn btn-success btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                
                                @if($schedule !== 'completed')
                                    <a href="{{ route('appointments.reschedule', ['appointment' => $appointment->id]) }}" class="btn btn-info btn-sm" title="Reschedule"><i class="bi bi-calendar2-week-fill"></i></a>
                                @endif
                            @endcan
                             
                            <form hx-post="{{ route('appointments.destroy', ['appointment' => $appointment->id]) }}" hx-target="body">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are you sure to cancel this appointment?')" class="btn btn-danger btn-sm" title="Cancel Appointment"><i class='bi bi-x-circle'></i></button>
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
        {{ $appointments->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>