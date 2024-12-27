<x-app-layout>

    <div class="col-md-12">
        <div class="page-header">
            <h3 class="fw-bold">Client Management</h3>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('history.index') }}" hx-get="{{ route('history.index') }}" hx-target="body">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)">Client History</a>
                </li>
            </ul>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <x-search-form :route="route('history.index')" :search="$search"></x-search-form>
        </div>

        <div id="table-data">
            @include('client_history.table-data')
        </div>


    </div>

</x-app-layout>