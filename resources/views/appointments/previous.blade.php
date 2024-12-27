<x-app-layout>
    <div class="pagetitle">
        <h1>Appointment Management</h1>
        <p class="text-dark"> <i class="bi bi-caret-right-fill"></i> Appointments</p>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-bordered">
                    <li class="nav-item">
                        <a href="{{ route('appointments.index') }}" class="nav-link">This Week</a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('appointments.previous') }}" class="nav-link active">Previous</a>
                    </li>
    
                    <li class="nav-item">
                        <a href="{{ route('appointments.upcoming') }}" class="nav-link">Upcoming</a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('appointments.completed') }}" class="nav-link">Completed</a>
                    </li>
                </ul>
                
                @include('appointments.table-data')
            </div>
        </div>
    </div>
</x-app-layout>