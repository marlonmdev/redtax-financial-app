<x-app-layout>
    <div class="pagetitle">
        <h1>User Management</h1>
        <p class="text-dark"> <a href="{{ route('users.index') }}">User Accounts</a> <i class="bi bi-caret-right-fill"></i> Edit</p>
    </div>
    
    <div class="mb-3">
        <a href="{{ route('users.index') }}" class="btn btn-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Go Back</a>
    </div>
    
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit User Profile</h5>
                                                                                     
                        <form hx-post="{{ route('users.update-profile', ['user' => $user->id]) }}" hx-target="body" class="col-lg-8 offset-r-4">
                            @csrf
                            @method('put')
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" placeholder="Name" value="{{ old('name', $user->name) }}">
                                <label for="name">Name</label>
                                @error('name')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                                <label for="email">Email</label>
                                @error('email')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Update</button>    
                            </div>
                        </form>
                    </div>
                </div>{{-- End of User Profile --}}
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit User Role</h5>
                        
                        @if (session()->has('role-success'))
                            <x-success-alert :message="session('role-success')"></x-success-alert>
                        @endif
                        
                        <form hx-post="{{ route('users.update-role', ['user' => $user->id]) }}" hx-target="body" class="col-lg-8 offset-r-4">
                            @csrf
                            @method('put')
                            
                            <div class="form-floating mb-3">
                                <select class="form-select {{ $errors->has('role') ? 'is-invalid' : '' }}" id="role" name="role">
                                    @if(!empty($roles))
                                        <option value=""  {{ old('role', $user->role_id) === '' ? 'selected' : '' }}>Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role', $user->role_id) === $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                        @endforeach                                            
                                    @else
                                        <option value="" selected>No Roles Added Yet</option>
                                    @endif
                                </select>
                                <label for="role">Role</label>
                                @error('role')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>

                            {{-- <div class="form-group d-flex gap-3 mb-3">
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
                            @enderror --}}

                            <div class="form-group d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>{{-- End of User Role --}}
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit User Password</h5>
                        
                        @if (session()->has('password-success'))
                            <x-success-alert :message="session('password-success')"></x-success-alert>
                        @elseif(session()->has('password-error'))
                            <x-error-alert :message="session('password-error')"></x-error-alert>
                        @endif
                        
                        <form hx-post="{{ route('users.update-password', ['user' => $user->id]) }}" hx-target="body" class="col-lg-8 offset-r-4">
                            @csrf
                            @method('put')
                            
                            <div class="form-floating mb-1">
                                <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="input-password" name="password" placeholder="Password">
                                <label for="input-password">Password</label>
                                @error('password')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex justify-content-end gap-1">
                                <a href="javascript:void(0)" class="fw-medium text-primary mx-4" onclick="generatePassword()" data-bs-toggle="tooltip" title="Generate Random Password"><i class="bi bi-key-fill"></i> Generate Password</a>

                                <input class="form-check-input" type="checkbox" onclick="showPassword()" aria-label="Checkbox for showing Password">
                                <label class="form-label">Show Password</label>
                            </div>  
                            
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" id="password-confirm" name="password_confirmation" placeholder="Confirm Password">
                                <label for="password-confirm">Confirm Password</label>
                                @error('password_confirmation')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
</x-app-layout>