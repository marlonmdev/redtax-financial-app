<div class="card">
    <div class="card-header">
        <div class="card-title">Permissions</div>
    </div>

    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Permission Name</th>
                    <th scope="col">Description</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="table-data">
                @forelse($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td>{{ $permission->permission_name }}</td>
                    <td>
                        {{ strlen($permission->description) > 40 ? substr($permission->description,0,40)."..." : $permission->description; }}
                    </td>
                    <td class="d-flex justify-content-center gap-2">
                        <a href="{{ route('permissions.edit', ['permission' => $permission->id]) }}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-pen"></i></a>

                        <form action="{{ route('permissions.destroy', ['permission' => $permission->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="return confirm('Are you sure to delete Permission?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-title="Delete"><i class='fas fa-trash-alt'></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center fw-bold">No Data Found...</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div>

    {{ $permissions->links() }}

</div>