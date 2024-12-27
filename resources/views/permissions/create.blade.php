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
                <span>Permissions</span>
            </li>
            <li class=" separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <span>Add</span>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Add Permission</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('permissions.store') }}" method="post" class="col-8 offset-r-4">
                            @csrf

                            <div class="form-group ">
                                <label>Permission Name</label>
                                <input type="text" class="form-control" name="permission_name" placeholder="Enter Permission Name" value="{{ old('permission_name') }}" autofocus />
                                @error('permission_name')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" wrap="physical" rows="5">
                                {{ old('description') }}
                                </textarea>
                                @error('description')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex gap-3">
                                <button type="submit" class="btn btn-success">SUBMIT</button>
                                <a href="{{ route('permissions.index') }}" class="btn btn-black">GO BACK</a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>