<div class="card">
    <div class="card-header">
        <div class="card-title">Roles</div>
    </div>

    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Role Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Permissions</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="table-data">
                @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->role_name }}</td>
                    <td>
                        {{ strlen($role->description) > 50 ? substr($role->description,0,50)."..." : $role->description; }}
                    </td>
                    <td class="text-primary text-start">
                        <strong>
                            @foreach ($role->permissions as $permission)
                            <span class="badge badge-black">{{ $permission->permission_name }}</span>
                            @endforeach
                        </strong>
                    </td>
                    <td class="d-flex justify-content-center gap-2">

                        <a href="{{ route('roles.edit', ['role' => $role->id]) }}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-pen"></i></a>

                        <form action="{{ route('roles.destroy', ['role' => $role->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="return confirm('Are you sure to delete Role?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-title="Delete"><i class='fas fa-trash-alt'></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center fw-bold">No Data Found...</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div>

    {{ $roles->links() }}

</div>