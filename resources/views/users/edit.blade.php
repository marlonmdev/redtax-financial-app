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
                <span>Users</span>
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
                    <div class="card-title">Edit User Profile</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form hx-post="{{ route('users.update-profile', ['user' => $user->id]) }}" hx-target="body" class="col-8 offset-r-4">
                            @csrf
                            @method('put')

                            <div class="form-group ">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name" value="{{ old('name', $user->name) }}" autofocus />
                                @error('name')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email" value="{{ old('email', $user->email) }}" />
                                @error('email')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex gap-3">
                                <button type="submit" class="btn btn-success">UPDATE</button>
                                <a href="{{ route('users.index') }}" class="btn btn-black">GO BACK</a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit User Role</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form hx-post="{{ route('users.update-role', ['user' => $user->id]) }}" hx-target="body" class="col-8 offset-r-4">
                            @csrf
                            @method('put')

                            <div class="form-group d-flex gap-3">
                                <label class="fw-medium">Select a Role:</label>
                                @forelse($roles as $role)
                                <div class="d-flex gap-1">
                                    <input class="form-check-input" type="checkbox" name="role_id[]" value="{{ $role->id }}" {{ in_array($role->id, $currentRoles) ? 'checked' : '' }}>
                                    <label class="fw-medium">{{ $role->role_name }}</label>
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
                                <a href="{{ route('users.index') }}" class="btn btn-black">GO BACK</a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit User Password</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form hx-post="{{ route('users.update-password', ['user' => $user->id]) }}" hx-target="body" class="col-8 offset-r-4">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" id="input-password" name="password" placeholder="Password" />
                                @error('password')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex justify-content-end gap-1">
                                <a href="javascript:void(0)" class="fw-bold text-danger mx-4" onclick="generatePassword()" data-bs-toggle="tooltip" title="Generate Random Password"><i class="fas fa-key"></i> Generate Password</a>

                                <input class="form-check-input" type="checkbox" onclick="showHiddenPassword()" aria-label="Checkbox for showing Password">
                                <label class="fw-bold">Show Password</label>
                            </div>

                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" />
                                @error('password_confirmation')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group d-flex gap-3">
                                <button type="submit" class="btn btn-success">UPDATE</button>
                                <a href="{{ route('users.index') }}" class="btn btn-black">GO BACK</a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>