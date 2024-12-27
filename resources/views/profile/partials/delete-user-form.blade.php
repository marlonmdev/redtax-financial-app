<div class="card mb-3">
    <div class="card-body">
        <div class="card-title mb-3">
            <h5>Delete Account</h5>
            <small>
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </small>
        </div>
        <form action="{{ route('profile.destroy') }}" method="post" class="col-lg-8 offset-r-4">
            @csrf
            @method('delete')
            
            <div class="form-floating mb-3">
                <input type="password" id="password" name="password" class="form-control {{ $errors->userDeletion->has('password') ? 'is-invalid' : '' }}" placeholder="Enter Your Password">
                <label for="password">Enter Your Password</label>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>
            

            <div class="d-flex justify-content-end align-items-center">
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </div>
        </form>
        
    </div>
</div>


