<x-app-layout>
    <div class="page-header">
        <h3 class="fw-bold">User Management</h3>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('users.index') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <span>Roles</span>
            </li>
            <li class=" separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <span>Edit</span>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Role</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('roles.update', ['role' => $role->id]) }}" method="post" class="col-8 offset-r-4">
                            @csrf
                            @method('put')

                            <div class="form-group ">
                                <label>Role Name</label>
                                <input type="text" class="form-control" name="role_name" placeholder="Enter Role Name" value="{{ old('role_name', $role->role_name) }}" autofocus />
                                @error('role_name')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control text-start" name="description" wrap="physical" rows="5">
                                {{ old('description', $role->description) }}
                                </textarea>
                                @error('description')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex gap-3">
                                <label class="fw-medium">Select Permissions:</label>
                                @forelse($permissions as $permission)
                                <div class="d-flex gap-1">
                                    <input class="form-check-input" type="checkbox" name="permission_id[]" value="{{ $permission->id }}" {{ in_array($permission->id, $currentPermissions) ? 'checked' : '' }}>
                                    <label class="fw-medium">{{ $permission->permission_name }}</label>
                                </div>
                                @empty
                                <span class="fw-bold">No Roles Added Yet</span>
                                @endforelse
                            </div>
                            @error('role_id')
                            <div class="form-group text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </div>
                            @enderror

                            <div class="form-group d-flex gap-3">
                                <button type="submit" class="btn btn-success">UPDATE</button>
                                <a href="{{ route('roles.index') }}" class="btn btn-black">GO BACK</a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>