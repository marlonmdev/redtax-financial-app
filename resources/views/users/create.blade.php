<x-app-layout>
    <div class="pagetitle">
        <h1>User Management</h1>
        <p class="text-dark"> <a href="{{ route('users.index') }}">User Accounts</a> <i class="bi bi-caret-right-fill"></i> Create</p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Create User</h5>
                        
                        <form hx-post="{{ route('users.store') }}" hx-target="body" class="col-lg-8 offset-r-4">
                            @csrf
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" placeholder="Name" value="{{ old('name') }}">
                                <label for="name">Name</label>
                                @error('name')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                                <label for="email">Email</label>
                                @error('email')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                 
                            <div class="form-floating mb-3">
                                <select class="form-select {{ $errors->has('role') ? 'is-invalid' : '' }}" id="role" name="role">
                                    <option value="" {{ old('role') === '' || old('role') === null ? 'selected' : '' }} >Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ (int) old('role') === $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                    @endforeach                                            
                                </select>
                                <label for="role">Role</label>
                                @error('role')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                         
                            
                            <div class="form-floating mb-1">
                                <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="input-password" name="password" placeholder="Password">
                                <label for="input-password">Password</label>
                                @error('password')
                                    <span class="text-danger">
                                        <p class="text-md fw-medium">{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group d-flex justify-content-end gap-1 mb-3">
                                <a href="javascript:void(0)" class="fw-medium text-primary mx-4" onclick="generatePassword()" data-bs-toggle="tooltip" title="Generate Random Password"><i class="bi bi-key-fill"></i> Generate Password</a>

                                <input class="form-check-input" type="checkbox" onclick="showPassword()" aria-label="Checkbox for showing Password">
                                <label>Show Password</label>
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

                            <div class="form-group d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a href="{{ route('users.index') }}" class="btn btn-dark">Go Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>