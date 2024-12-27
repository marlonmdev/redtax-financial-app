<x-app-layout>

    <div class="col-md-12">
        <div class="page-header">
            <h3 class="fw-bold">User Management</h3>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('users.index') }}" hx-get="{{ route('users.index') }}" hx-target="body">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)">User Accounts</a>
                </li>
            </ul>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('users.create') }}" class="btn btn-secondary">Add User</a>
            </div>
            <x-search-form :route="route('users.index')" :search="$search"></x-search-form>
        </div>

        <div id="table-data">
            @include('users.table-data')
        </div>


    </div>

</x-app-layout>