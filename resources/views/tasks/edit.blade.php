<x-app-layout>
    <div class="pagetitle">
        <h1>Task Management</h1>
        <p class="text-dark"> <a href="{{ route('tasks.index') }}">Tasks</a> <i class="bi bi-caret-right-fill"></i> Edit</p>
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
                
                                <li class="nav-item">
                                    <a href={{ route('tasks.edit',  ['task' => $task->id]) }} class="nav-link active">Edit Tasks</a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href={{ route('tasks.notes',  ['task' => $task->id]) }} class="nav-link">Task Notes</a>
                                </li>  
                            </div>
                            <div class="d-flex justify-content-end align-items-center">
                                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#noteModal">
                                    Add Note
                                </button>
                            </div>     
                        </ul>
                                                                                                   
                        <form hx-post="{{ route('tasks.update',  ['task' => $task->id]) }}" hx-target="body">
                            @csrf
                            @method('put')
                            
                            <input type="hidden" id="assign-to-id" name="assign_to_id" value="{{ old('assign_to_id', $task->user_id) }}">
                            
                            <div class="row">                                
                                <div class="col-lg-6">
                                    <div class="form-floating mb-4">
                                        <input type="text" class="form-control {{ $errors->has('assign_to') || $errors->has('assign_to_id') ? 'is-invalid' : '' }}" id="assign-to" name="assign_to" placeholder="Assign to: *Start by typing a name..." value="{{ old('assign_to', $task->assigned_to) }}" oninput="searchforTask()">
                                        <label for="assign-to">Assign to *Search name...</label>
                                        
                                        <div id="search-results" class="d-none"></div>
                                        <div class="d-flex flex-column gap-1">
                                            @error('assign_to')
                                                <span class="text-danger text-md fw-medium">{{ $message }}</span>
                                            @enderror
                                            
                                            @error('assign_to_id')
                                                <span class="text-danger text-md fw-medium">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <select class="form-select {{ $errors->has('priority') ? 'is-invalid' : '' }}" id="priority" name="priority">
                                            @foreach($priorityTypes as $priorityType)
                                                <option value="{{ $priorityType->value }}" {{ old('priority', $task->priority) === $priorityType->value ? 'selected' : '' }}>{{ $priorityType->value }}</option>
                                            @endforeach
                                        </select>
                                        <label for="priority">Priority</label>
                                        @error('priority')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-lg-3">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control {{ $errors->has('due_date') ? 'is-invalid' : '' }}" id="datepicker" name="due_date" placeholder="Due Date (dd/mm/yyyy)" value="{{ old('due_date', $task->due_date) }}">
                                        <label for="datepicker">Due Date (dd/mm/yyyy)</label>
                                        @error('due_date')
                                            <span class="text-danger">
                                                <p class="text-md fw-medium">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" id="title" name="title" placeholder="title" value="{{ old('title', $task->title) }}">
                                <label for="title">Title</label>
                                @error('title')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-floating mb-3">
                                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" id="description" name="description" placeholder="Description" style="height:150px;">{{ old('description', $task->description) }}</textarea>
                                <label for="description">Description</label>
                                
                                @error('description')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group justify-content-end d-flex gap-2">
                                @can('update', $task)
                                    <button type="submit" class="btn btn-success">Update</button>
                                @endcan
                                <a href="{{ route('tasks.index') }}" class="btn btn-dark">Go Back</a>
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @include('tasks.notes-modal')
    
</x-app-layout>