<div class="col-lg-12">
    <div class="card overflow-auto">     

      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">Recent Clients</h5>
          <a href="{{ route('clients.index') }}" class="text-primary fw-medium fs-6">View More<i class="bi bi-caret-right-fill"></i></a>
        </div>

        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Address</th>
              <th scope="col">Added On</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentClients as $recentClient)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">  
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($recentClient->name) }}&background=random" alt="client avatar" width="24" height="auto" class="rounded-circle">
                            <span>{{ $recentClient->name }}</span>
                        </div>
                    </td>
                    <td>{{ $recentClient->email }}</td>
                    <td>{{ $recentClient->address }}</td>
                    <td>
                      <!-- using formatDate helper -->
                      {{ formatDate($recentClient->added_on) }}
                    </td>
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