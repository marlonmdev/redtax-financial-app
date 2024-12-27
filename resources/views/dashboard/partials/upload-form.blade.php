<div class="col-lg-12">
    <div class="card overflow-auto">     
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">Upload Your Documents</h5>
        </div>
        <form class="mb-3" hx-post="{{ route('documents.upload-files') }}" hx-target="body" enctype="multipart/form-data">
          @csrf
          <div class="d-flex align-items-end gap-2">
              <div class="form-group">
                  <label class="pb-1">*Documents to Upload </label><span class="badge text-bg-secondary text-danger ms-1">JPG, PDF, Doc, Excel - multiple files are automatically zip upon submission</span>
                  <input type="file" id="document" name="document[]" class="form-control px-3 py-3 {{ $errors->has('document') || $errors->has('document.*') ? 'is-invalid' : '' }}" accept=".jpg, jpeg, .csv, .pdf, .doc, .docx, .xls, .xlsx" multiple>
              </div>
              
              <div class="d-flex justify-content-end align-items-center gap-2">
                  <button type="submit" class="btn btn-success p-3">UPLOAD<i class="bi bi-upload ms-2"></i></button>
              </div>
          </div>
          @error('document')
              <span class="text-danger">
                  <p class="text-md fw-medium">{{ $message }}</p>
              </span>
          @enderror
          
          @error('document.*')
              @foreach ($errors->get('document.*') as $messages)
                  @foreach ($messages as $message)
                      <span class="text-danger text-md fw-medium me-1">
                          {{ $message }}
                      </span>
                  @endforeach
              @endforeach
          @enderror
        </form>    
      </div>
    </div>
</div>