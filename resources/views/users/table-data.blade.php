<div class="card">
    <div class="card-header">
        <div class="card-title">User Accounts</div>
    </div>

    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Roles</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="table-data">
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-primary text-start">
                        <strong>
                            @foreach ($user->roles as $role)
                            <span class="badge badge-black">{{ $role->role_name }}</span>
                            @endforeach
                        </strong>
                    </td>
                    <td class="d-flex justify-content-center gap-2">
                        @csrf

                        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-title="View"><i class="fas fa-eye"></i></a>

                        <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-pen"></i></a>

                        <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="return confirm('Are you sure to delete User?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-title="Delete"><i class='fas fa-trash-alt'></i></button>
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
    {{ $users->links() }}
</div>