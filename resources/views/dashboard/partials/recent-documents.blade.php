<div class="col-12">
    <div class="card overflow-auto">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">Recent Documents</h5>
          <a href="{{ route('documents.index') }}" class="text-primary fw-medium fs-6">View More <i class="bi bi-caret-right-fill"></i></a>
        </div>
        
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Document Name</th>
              <th scope="col">Uploaded By</th>
              <th scope="col">Uploaded On</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentDocuments as $recentDocument)
                <tr>
                  <td>{{ $recentDocument->document_name }}</td>
                  <td>
                      <div class="d-flex align-items-center gap-2">  
                          <img src="https://ui-avatars.com/api/?name={{ urlencode($recentDocument->client->name) }}&background=random" alt="client avatar" width="24" height="auto" class="rounded-circle">
                          <span>{{ $recentDocument->client->name }}</span>
                      </div>
                  </td>
              
                  <td>{{ formatDate($recentDocument->upload_date) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center fw-bold">No Records Yet...</td>
                </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
</div>