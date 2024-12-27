<div class="col-12">
    <div class="card overflow-auto">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">This Week's Appointments</h5>
          <a href="{{ route('appointments.index') }}" class="text-primary fw-medium fs-6">View More <i class="bi bi-caret-right-fill"></i></a>
        </div>
        
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Client Name</th>
              <th scope="col">Service</th>
              <th scope="col">Date</th>
              <th scope="col">Time</th>
            </tr>
          </thead>
          <tbody>
            @forelse($weeklyAppointments as $appointment)
                <tr>
                  <td>
                      <div class="d-flex align-items-center gap-2">  
                          <img src="https://ui-avatars.com/api/?name={{ urlencode($appointment->name) }}&background=random" alt="client avatar" width="24" height="auto" class="rounded-circle">
                          <span>{{ $appointment->name }}</span>
                      </div>
                  </td>
                  <td>{{ $appointment->service->service }}</td>
                  <td>{{ date('l - M d, Y', strtotime($appointment->date)) }}</td>
                  <td>{{ date('g:i A', strtotime($appointment->start_time)) }} - {{ date('g:i A', strtotime($appointment->end_time)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center fw-bold">No Records Yet...</td>
                </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
</div>