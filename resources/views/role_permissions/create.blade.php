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
                <span>Add Permissions</span>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Add Role Permissions</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('role-permissions.store') }}" method="post" class="col-12">
                            @csrf
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                            <div class="form-group col-md-6 col-xs-12">
                                <label>Current Role</label>
                                <input type="text" class="form-control" value="{{ $role->role_name }}" readonly />
                            </div>
                            <div class="form-group">
                                <span class="fw-bold">All Role Permissions</span>
                            </div>
                            <div class="form-group d-flex gap-3">
                                @forelse($permissions as $permission)

                                <div class="d-flex gap-2">
                                    <input class="form-check-input" type="checkbox" name="permission_id[]" value="{{ $permission->id }}" aria-label="Checkbox for Roles">
                                    <label class="fw-bold"><strong>{{ $permission->permission_name }}</strong></label>
                                </div>
                                @empty
                                <span class="fw-bold">No Roles Added Yet</span>
                                @endforelse
                            </div>
                            @error('permission_id')
                            <div class="form-group text-danger">
                                <p class="text-md fw-medium">{{ $message }}</p>
                            </div>
                            @enderror

                            <div class="form-group d-flex gap-3">
                                <button type="submit" class="btn btn-success">SUBMIT</button>
                                <a href="{{ route('roles.index') }}" class="btn btn-black">GO BACK</a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>