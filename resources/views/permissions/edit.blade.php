<x-app-layout>
    <div class="pagetitle">
        <h1>User Management</h1>
        <p class="text-dark"> <a href="{{ route('permissions.index') }}">Permissions</a> <i class="bi bi-caret-right-fill"></i> Edit</p>
    </div>
    
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Permission</h5>
                        
                        <form hx-post="{{ route('permissions.update', ['permission' => $permission->id]) }}" hx-target="body" class="col-lg-8 offset-r-4">
                            @csrf
                            @method('put')
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control {{ $errors->has('permission_name') ? 'is-invalid' : '' }}" id="permission-name" name="permission_name" placeholder="Permission Name" value="{{ old('permission_name', $permission->permission_name) }}">
                                <label for="permission-name">Permission Name</label>
                                @error('permission_name')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-floating mb-3">
                                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" id="description" name="description" placeholder="Description" style="height:150px;">{{ old('description', $permission->description) }}</textarea>
                                <label for="description">Description</label>
                                
                                @error('description')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('permissions.index') }}" class="btn btn-dark">Go Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>