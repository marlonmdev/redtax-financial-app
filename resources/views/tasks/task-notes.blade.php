<x-app-layout>
    <div class="pagetitle">
        <h1>Task Management</h1>
        <p class="text-dark"> <a href="{{ route('tasks.index') }}">Tasks</a> <i class="bi bi-caret-right-fill"></i>Task Notes</p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">                        
                        <ul class="d-flex justify-content-between nav nav-tabs nav-tabs-bordered mb-4">
                            <div class="d-flex">
                                <li class="nav-item">
                                    <a href={{ route('tasks.preview',  ['task' => $task->id]) }} class="nav-link">Preview</a>
                                </li>
                                
                                @can('update', $task)
                                    <li class="nav-item">
                                        <a href={{ route('tasks.edit',  ['task' => $task->id]) }} class="nav-link">Edit Tasks</a>
                                    </li>
                                @endcan
                                
                                <li class="nav-item">
                                    <a href={{ route('tasks.notes',  ['task' => $task->id]) }} class="nav-link active">Task Notes</a>
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
                        
                        @if($task->notes->isNotEmpty())
                            <div class="table-responsive mt-4" id="task-notes">
                                <table class="table table-warning table-hover table-sm">
                                    <thead>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Note</th>
                                        <th>Added On</th>
                                        <th class="text-center">Actions</th>
                                    </thead>
                                    <tbody>
                                        @forelse ($task->notes as $note)
                                            <tr>
                                                <td>{{ $note->sender ? $note->sender->name : '' }}</td>  
                                                <td>{{ $note->recipient ? $note->recipient->name : '' }}</td>
                                                <td>
                                                    {{ strlen($note->note) > 50 ? substr($note->note, 0, 50)."..." : $note->note; }}
                                                </td>
                                                <td>{{ $note->created_at ? date('M d, Y g:i A', strtotime($note->created_at)) : '' }}</td>
                                                <td>
                                                
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button class="btn btn-sm btn-primary btn-sm edit-note" title="View" 
                                                            data-note-id="{{ $note->id }}"
                                                            data-note="{{ $note->note }}"
                                                            data-sender="{{ $note->sender->id }}"
                                                            data-recipient="{{ $note->recipient->name }}"
                                                            onclick="viewNoteModal(this)">
                                                                <i class="bi bi-eye-fill"></i>
                                                        </button>
                                                        
                                                        @if(auth()->user()->id === $note->sender->id)
                                                            <button class="btn btn-sm btn-success btn-sm edit-note" title="Edit" 
                                                                data-note-id="{{ $note->id }}"
                                                                data-note="{{ $note->note }}"
                                                                data-sender="{{ $note->sender->id }}"
                                                                data-recipient="{{ $note->recipient->id }}"
                                                                onclick="editNoteModal(this)">
                                                                    <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            
                                                            <form hx-post="{{ route('notes.destroy', ['note' => $note->id]) }}" hx-target="body">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" onclick="return confirm('Are you sure to delete note?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center fw-bold">No Notes Yet...</td>
                                            </tr>
                                        @endforelse   
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <h5 class="text-center fw-medium">No Task Notes Available...</h5>
                        @endif   
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @include('tasks.notes-modal')
    
</x-app-layout>