<x-app-layout>
    <div class="pagetitle">
        <h1>Access Requests</h1>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">                
                <div class="card">
                    <div class="card-body">
                        @include('access_requests.table-data')
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>