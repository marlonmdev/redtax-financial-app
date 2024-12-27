<x-app-layout>
    <div class="pagetitle">
        <h1>User Management</h1>
        <p class="text-dark"> <a href="{{ route('roles.index') }}">Roles</a> <i class="bi bi-caret-right-fill"></i> Edit</p>
    </div>
    
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Role</h5>
                        
                        <form hx-post="{{ route('roles.update', ['role' => $role->id]) }}" hx-target="body" class="col-lg-8 offset-r-4">
                            @csrf
                            @method('put')
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control {{ $errors->has('role_name') ? 'is-invalid' : '' }}" id="role-name" name="role_name" placeholder="Role Name" value="{{ old('role_name', $role->role_name) }}">
                                <label for="role-name">Role Name</label>
                                @error('role_name')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-floating mb-3">
                                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" id="description" name="description" placeholder="Description" style="height:150px;">{{ old('description', $role->description) }}</textarea>
                                <label for="description">Description</label>
                                
                                @error('description')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex gap-3 mb-3">
                                <label class="fw-medium">Select Permissions:</label>
                                @forelse($permissions as $permission)
                                <div class="d-flex gap-1">
                                    <input class="form-check-input" type="checkbox" name="permission_id[]" value="{{ $permission->id }}" {{ in_array($permission->id, $currentPermissions) ? 'checked' : '' }}>
                                    <label class="fw-medium">{{ $permission->permission_name }}</label>
                                </div>
                                @empty
                                <span class="fw-bold">No Permissions Added Yet</span>
                                @endforelse
                            </div>
                            @error('permission_id')
                                <div class="form-group text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </div>
                            @enderror

                            <div class="form-group d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('roles.index') }}" class="btn btn-dark">Go Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>