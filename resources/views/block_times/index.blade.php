<x-app-layout>
    <div class="pagetitle">
        <h1>Appointment Management</h1>
        <p class="text-dark"> <i class="bi bi-caret-right-fill"></i> Block Times</p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-start align-items-center mb-3">
                    <div>
                        <a href="{{ route('block-times.create') }}" class="btn btn-dark"><i class="bi bi-plus-circle me-1"></i>Create</a>
                    </div>
                </div>   
                
                <div class="card">
                    <div class="card-body">
                        @include('block_times.table-data')
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>