<x-app-layout>
    <div class="pagetitle">
        <h1>User Management</h1>
        <p class="text-dark"> <i class="bi bi-caret-right-fill"></i> Audit Logs</p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('audit_logs.table-data')
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>