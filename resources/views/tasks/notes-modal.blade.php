    <!-- Note Modal -->
    <div class="modal fade" id="noteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Note</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('notes.store') }}" method="post">        
                        @csrf 
                        
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <input type="hidden" name="message_by" value="{{ auth()->user()->id }}">
                        
                        <div class="form-floating mb-3">
                            <select class="form-select {{ $errors->has('users') ? 'is-invalid' : '' }}" id="message-to" name="message_to" required>
                                <option value="">Select a user to message</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('message_to') === $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            
                            <label for="message-to">Message to</label>
                            @error('message_to')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-floating mb-3">
                            <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" id="note" name="note" placeholder="Enter Note" style="height:240px;" required>{{ old('note') }}</textarea>
                            
                            <label for="note">Enter Note</label>
                            @error('note')
                                <span class="text-danger">
                                    <p class="text-md fw-medium"></p>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group justify-content-end d-flex gap-2">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Note Modal -->
    
    <!-- View Note Modal -->
    <div class="modal fade" id="viewNoteModal" tabindex="-1" aria-labelledby="viewNoteLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="viewNoteForm" action="" method="POST">
                        @csrf
                        
                        <div class="form-floating mb-3">      
                            <input type="text" class="form-control " id="view-message-to" placeholder="Message To" readonly>
                            <label for="view-message-to">Message to</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="view-note" style="height:240px;" readonly></textarea>
                            <label for="view-note">Note</label>
                        </div>

                        <div class="form-group justify-content-end d-flex gap-2">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of View Note Modal -->
    
    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-labelledby="editNoteLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editNoteForm" action="" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" id="note-id" name="note_id">
                        
                        <div class="form-floating mb-3">
                            <select class="form-select" id="edit-message-to" name="message_to" required>
                                <option value="">Select a user to message</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <label for="edit-message-to">Message to</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="edit-note" name="note" style="height:240px;" required></textarea>
                            <label for="edit-note">Note</label>
                        </div>

                        <div class="form-group justify-content-end d-flex gap-2">
                            <button type="submit" id="update-button" class="btn btn-success">Update</button>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Edit Note Modal -->