<x-app-layout>
    <div class="pagetitle">
        <h1>Upload Management</h1>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">               
                <div class="card">
                    <div class="card-body">
                        @include('uploads.table-data')
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>