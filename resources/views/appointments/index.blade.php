<x-app-layout>
    <div class="pagetitle">
        @if(Gate::allows('accessRestrictedPages'))
            <h1>Appointment Management</h1>
            <p class="text-dark"> <i class="bi bi-caret-right-fill"></i> Appointments</p>
        @else
            <h1>My Appointments</h1>
        @endif
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @if(Gate::allows('accessRestrictedPages'))
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <a href="{{ route('appointments.index') }}" class="nav-link active">This Week</a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('appointments.previous') }}" class="nav-link">Previous</a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('appointments.upcoming') }}" class="nav-link">Upcoming</a>
                        </li>
        
                        <li class="nav-item">
                            <a href="{{ route('appointments.completed') }}" class="nav-link">Completed</a>
                        </li>
                    </ul>
                @endif
                
                @include('appointments.table-data')
            </div>
        </div>
    </div>
</x-app-layout>