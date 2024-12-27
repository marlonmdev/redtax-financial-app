<x-app-layout>

    <div class="col-md-12">
        <div class="page-header">
            <h3 class="fw-bold">Client Management</h3>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('clients.index') }}" hx-get="{{ route('clients.index') }}" hx-target="body">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)">Client Profiles</a>
                </li>
            </ul>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('clients.create') }}" class="btn btn-secondary">Add Client</a>
            </div>

            <x-search-form :route="route('clients.index')" :search="$search"></x-search-form>
        </div>

        <div id="table-data">
            @include('clients.table-data')
        </div>


    </div>

</x-app-layout>