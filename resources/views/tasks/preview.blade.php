<x-app-layout>
    <div class="pagetitle">
        <h1>Task Management</h1>
        <p class="text-dark"> <a href="{{ route('tasks.index') }}">Tasks</a> <i class="bi bi-caret-right-fill"></i> Preview</p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">                        
                        <ul class="d-flex justify-content-between nav nav-tabs nav-tabs-bordered mb-4">
                            <div class="d-flex">
                                <li class="nav-item">
                                    <a href={{ route('tasks.preview',  ['task' => $task->id]) }} class="nav-link active">Preview</a>
                                </li>
                                
                                @can('update', $task)
                                    <li class="nav-item">
                                        <a href={{ route('tasks.edit',  ['task' => $task->id]) }} class="nav-link">Edit Tasks</a>
                                    </li>
                                @endcan
                                
                                <li class="nav-item">
                                    <a href={{ route('tasks.notes',  ['task' => $task->id]) }} class="nav-link">Task Notes</a>
                                </li>  
                            </div>
                            @if(Gate::allows('accessRestrictedPages'))
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#noteModal">
                                        Add Note
                                    </button>
                                </div>   
                            @endif  
                        </ul>        
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-medium text-nowrap">Assigned By:</td>
                                        <td class="fst-italic">{{ $task->assigned_by }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Assigned To:</td>
                                        <td class="fst-italic">{{ $task->assigned_to }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Priority:</td>
                                        <td class="fst-italic">{{ $task->priority }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Due Date:</td>
                                        <td class="fst-italic">{{ date('F d, Y', strtotime($task->due_date)) }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Title:</td>
                                        <td class="fst-italic">{{ $task->title }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Description:</td>
                                        <td class="fst-italic">{{ $task->description }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Status:</td>
                                        <td class="fst-italic">{{ $task->status }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="fw-medium text-nowrap">Date Created:</td>
                                        <td class="fst-italic">{{ date('F d, Y', strtotime($task->created_at)) }}</td>
                                    </tr>
                                </tbody>
                            </table> 
                        </div>

                        
                        <div class="form-group justify-content-end d-flex gap-2">
                            @can('update', $task)
                                <a href="{{ route('tasks.edit', ['task' => $task->id]) }}" class="btn btn-success" title="Edit Task">Edit</a>
                            @endcan
                            
                            @can('delete', $task)
                                <form hx-post="{{ route('tasks.destroy', ['task' => $task->id]) }}" hx-target="body">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirm('Are you sure to delete task?')" class="btn btn-danger" title="Delete Task">Delete</button>
                                </form>
                            @endcan
                            
                            <a href="{{ route('tasks.index') }}" class="btn btn-dark">Close</a>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @include('tasks.notes-modal')
    
</x-app-layout>