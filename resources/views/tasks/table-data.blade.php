<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="tasks.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('tasks.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('tasks.index')" :search="$search"></x-search-form>
    </div>
   
</div>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <x-sortable-header 
                field="id" 
                :current-field="request('sort_field')" 
                :current-direction="request('sort_direction')" 
                route="tasks.index" 
                :search="$search"
                :per-page="$perPage"
                >
                Date
            </x-sortable-header>
            
            <x-sortable-header 
                field="assigned_to" 
                :current-field="request('sort_field')" 
                :current-direction="request('sort_direction')" 
                route="tasks.index" 
                :search="$search"
                :per-page="$perPage"
                >
                Assigned To
            </x-sortable-header>
            
            <x-sortable-header 
                field="title" 
                :current-field="request('sort_field')" 
                :current-direction="request('sort_direction')" 
                route="tasks.index" 
                :search="$search"
                :per-page="$perPage"
                >
                Title
            </x-sortable-header>
            <th>Notes</th>
            <th>Priority</th>
            <th class="text-center">Status</th>
            <th class="text-center">
                @php 
                    echo auth()->user()->role->role_name !== 'Client' ? 'Actions' : '';
                @endphp
            </th>
        </thead>
        <tbody id="table-data">
            @forelse($tasks as $task)
            <tr>
                <td>{{ date('m/d/Y', strtotime($task->created_at)) }}</td>
                <td>  
                    <a href={{ route('tasks.preview',  ['task' => $task->id]) }} class="text-dark" title="Click to preview task">
                        <div class="d-flex align-items-center gap-2">  
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assigned_to) }}&background=random" alt="user avatar" width="24" height="auto" class="rounded-circle">
                            <span class="link-underline-dark">{{ $task->assigned_to }}</span>
                        </div>
                    </a>
                </td>
                <td>{{ $task->title }}</td>
                <td class="text-center">
                    @php 
                        $hasNotes = $task->notes_count > 0 ? true: false;
                    @endphp
                    @if($hasNotes)
                        <a href={{ route('tasks.notes',  ['task' => $task->id]) }} class="text-dark">
                            <span class="badge rounded-pill text-bg-danger">{{ $task->notes_count }} Note/s</span>
                        </a>
                    @else
                        <p>None</p>
                    @endif
                </td>
                <td>{{ $task->priority ?? '' }}</td>
                <td>
                    @if(Gate::denies('isClient'))     
                        <form hx-post="{{ route('tasks.update-status', $task->id) }}" hx-trigger="change" hx-target="body">
                            @csrf
                            @method('PUT')
                            <select class="form-select" id="status" name="status">
                                @foreach($statusTypes as $statusType)
                                    <option value="{{ $statusType->value }}" {{ $task->status === $statusType->value ? 'selected' : '' }}>
                                        {{ $statusType->value }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @else
                        <p class="text-center">{{ $task->status }}</p>
                    @endif
                </td>
                <td>
                    <div class="d-flex justify-content-end gap-2">                         
                        <a href="{{ route('tasks.preview',  ['task' => $task->id]) }}" class="btn btn-primary btn-sm" title="View Task"><i class="bi bi-eye-fill"></i></a>
                        
                        @can('update', $task)
                            <a href="{{ route('tasks.edit', ['task' => $task->id]) }}" class="btn btn-success btn-sm" title="Edit Task"><i class="bi bi-pencil-square"></i></a>
                        @endcan

                        @can('delete', $task)
                            <form hx-post="{{ route('tasks.destroy', ['task' => $task->id]) }}" hx-target="body">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are you sure to delete task?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center fw-bold">No Records Found...</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $tasks->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>