<x-app-layout>
    <div class="pagetitle">
        <h1>Task Management</h1>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div>
                        @if(Gate::allows('accessRestrictedPages'))
                            <a href="{{ route('tasks.create') }}" class="btn btn-dark"><i class="bi bi-plus-circle me-1"></i>Create</a>
                        @endif
                    </div> 
                   
                    <div class="d-flex gap-2">
                        @if($taskNotesCount > 0)
                            <button class="btn btn-danger"
                                hx-get="{{ route('tasks.index') }}" 
                                hx-trigger="click" 
                                hx-target="body" 
                                hx-vals='{
                                    "search": "",
                                    "per_page": "{{ $perPage }}",
                                    "page": "{{ request('page', 1) }}",
                                    "filter_notes": true
                                }'>
                                Notes For You <span class="badge rounded-pill text-bg-light fw-medium fs-6">{{ $taskNotesCount }}</span>
                            </button>
                        @endif
                        
                        <button class="btn btn-secondary"
                            hx-get="{{ route('tasks.index') }}" 
                            hx-trigger="click" 
                            hx-target="body" 
                            hx-vals='{
                                "search": "Not Started",
                                "per_page": "{{ $perPage }}",
                                "page": "{{ request('page', 1) }}"
                            }'>
                            Not Started <span class="badge rounded-pill text-bg-light fw-medium fs-6">{{ $count['not-started-tasks'] }}</span>
                        </button>
                        
                        <button class="btn btn-warning"
                            hx-get="{{ route('tasks.index') }}" 
                            hx-trigger="click" 
                            hx-target="body" 
                            hx-vals='{
                                "search": "Pending",
                                "per_page": "{{ $perPage }}",
                                "page": "{{ request('page', 1) }}"
                            }'>
                            Pending <span class="badge rounded-pill text-bg-light fw-medium fs-6">{{ $count['pending-tasks'] }}</span>
                        </button>
                        
                        <button class="btn btn-primary"
                            hx-get="{{ route('tasks.index') }}" 
                            hx-trigger="click" 
                            hx-target="body" 
                            hx-vals='{
                                "search": "In Progress",
                                "per_page": "{{ $perPage }}",
                                "page": "{{ request('page', 1) }}"
                            }'>
                            In Progress <span class="badge rounded-pill text-bg-light fw-medium fs-6">{{ $count['in-progress-tasks'] }}</span>
                        </button>
                        
                        <button class="btn btn-success"
                            hx-get="{{ route('tasks.index') }}" 
                            hx-trigger="click" 
                            hx-target="body" 
                            hx-vals='{
                                "search": "Completed",
                                "per_page": "{{ $perPage }}",
                                "page": "{{ request('page', 1) }}"
                            }'>
                            Completed <span class="badge rounded-pill text-bg-light fw-medium fs-6">{{ $count['completed-tasks'] }}</span>
                        </button>
                    </div>                 
                </div>
                
                <div class="card">
                    <div class="card-body">
                        @include('tasks.table-data')
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>