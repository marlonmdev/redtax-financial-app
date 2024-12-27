<div class="col-lg-10 offset-1">
    <div class="card p-5">   
        @include('dashboard.partials.engagement-letter-content')
            
        <div class="d-flex justify-content-end align-items-center gap-2">
            <form action="{{ route('user.agree-to-terms', ['user' => auth()->user()->id]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-dark">Agree to Terms & Conditions</button>
            </form>
            <a href="{{ route('save.pdf') }}" class="btn btn-danger">Download PDF</a>
        </div>
    </div>
</div>

