<!-- Clients Card -->
  @if(Gate::allows('accessRestrictedPages'))
    <div class="col-lg-4">
      <div class="card info-card sales-card">
        <div class="card-body">          
          <div class="d-flex justify-content-between align-items-baseline">
            <h5 class="card-title">Total Clients</h5>
            @if($count['accessRequests'] > 0)              
              <a href="{{ route('access-requests.index') }}" class="btn btn-outline-danger rounded-pill btn-sm">
                <span class="badge text-bg-danger">{{ $count['accessRequests'] }}</span> Access Request{{ $count['accessRequests'] > 1 ? 's': '' }}
              </a>
            @endif
          </div>

          <div class="d-flex align-items-center">
            <div
              class="card-icon rounded-circle d-flex align-items-center justify-content-center"
            >
              <i class="bi bi-people"></i>
            </div>
            <div class="d-flex align-items-end gap-4 ps-3">
              <h6>{{ $count['clients'] }}</h6>
              <a href="{{ route('clients.index') }}" class="text-primary fw-medium fs-6">View More<i class="bi bi-caret-right-fill"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
  <!-- End Clients Card -->

  <!-- Documents Card -->
  <div class="col-lg-4">
    <div class="card info-card revenue-card">
      <div class="card-body">
        <h5 class="card-title">
          Documents Uploaded</span>
        </h5>

        <div class="d-flex align-items-center">
          <div
            class="card-icon rounded-circle d-flex align-items-center justify-content-center"
          >
            <i class="bi bi-file-earmark-text"></i>
          </div>
          <div class="d-flex align-items-end gap-4 ps-3">
            <h6>{{ $count['documents'] }}</h6>
            <a href="{{ route('documents.index') }}" class="text-primary fw-medium fs-6">View More<i class="bi bi-caret-right-fill"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Documents Card -->

  <!-- Tasks Card -->
  <div class="col-lg-4">
    <div class="card info-card customers-card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-baseline">
          <h5 class="card-title">Task</h5>
          @if($count['taskNotes'] !== 0)            
            <form action="{{ route('tasks.index') }}" method="GET">
              <input type="hidden" name="search" value="">

              <button type="submit" name="filter_notes" value="1" class="btn btn-outline-danger rounded-pill btn-sm">
                <span class="badge text-bg-danger">{{ $count['taskNotes'] }}</span> Task Note{{ $count['taskNotes'] > 1 ? 's': '' }} For You 
              </button>
            </form>
          @endif
        </div>

        <div class="d-flex align-items-center">
          <div
            class="card-icon rounded-circle d-flex align-items-center justify-content-center"
          >
            <i class="bi bi-bar-chart-steps"></i>
          </div>
          <div class="d-flex align-items-end gap-4 ps-3">
            <h6>{{ $count['tasks'] }}</h6>
            <a href="{{ route('tasks.index') }}" class="text-primary fw-medium fs-6">View More<i class="bi bi-caret-right-fill"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Tasks Card -->
  
  @if(Gate::allows('isClient'))
    <!-- Client Engagement PDF Download Card -->
    <div class="col-lg-4">
      <div class="card info-card sales-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-baseline">
            <h5 class="card-title">Client Engagement Letter</h5>
          </div>

          <div class="d-flex align-items-center">
            <div
              class="card-icon rounded-circle d-flex align-items-center justify-content-center"
            >
              <i class="bi bi-file-earmark-check"></i>
            </div>
            <div class="d-flex align-items-end gap-4 ps-3">
              <a href="{{ route('save.pdf') }}" class="btn btn-danger">Download PDF</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Client Engagement PDF Download  Card -->
  @endif