<x-app-layout>
    <div class="pagetitle">
        <h1>Client Management</h1>
        <p class="text-dark"> <a href="{{ route('clients.index') }}">Client Profiles</a> <i class="bi bi-caret-right-fill"></i> Show</p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                
                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <a href="{{ route('clients.show-profile',  ['client' => $client_id]) }}" class="nav-link">Profile</a>
                            </li>
                            
                            {{-- <li class="nav-item">
                                <a href="#" class="nav-link">Personal Information</a>
                            </li>  --}}
                            
                            <li class="nav-item">
                                <a href="{{ route('clients.show-documents',  ['client' => $client_id]) }}" class="nav-link active">Documents</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-2">
                            <div class="tab-pane fade show active">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    
                                    <div class="d-flex align-items-center gap-2">  
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($client_name) }}&background=random" alt="client avatar" width="40" height="auto" class="rounded-circle">
                                        <span class="fw-medium fs-5">{{ $client_name }}</span>
                                    </div>

                                    <div class="d-flex gap-3">
                                        <a hx-get="{{ route('clients.show-documents', ['client' => $client_id]) }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat me-1"></i></a>
                                        <x-search-form :route="route('clients.show-documents', ['client' => $client_id])" :search="$search"></x-search-form>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>Name</th>
                                            <th>Uploaded On</th>
                                            <th>Size</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Actions</th>
                                        </thead>
                                        <tbody id="table-data">
                                            @forelse($documents as $document)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex justify-content-start align-items-center gap-2">
                                                            @php
                                                                $icons = [
                                                                    'png' => 'bi-image text-success fs-4',
                                                                    'jpeg' => 'bi-image text-success fs-4',
                                                                    'jpg' => 'bi-image text-success fs-4',
                                                                    'pdf' => 'bi-filetype-pdf text-danger fs-4',
                                                                    'doc' => 'bi-filetype-doc text-primary fs-4',
                                                                    'docx' => 'bi-filetype-docx text-primary fs-4',
                                                                    'xls' => 'bi-filetype-xls text-success fs-4',
                                                                    'xlsx' => 'bi-filetype-xlsx text-success fs-4',
                                                                    'csv' => 'bi-filetype-csv text-success fs-4',
                                                                    'zip' => 'bi-file-zip-fill text-warning fs-4',
                                                                ];
    
                                                                $iconClass = $icons[strtolower($document->document_extension)] ?? 'bi-file-earmark fs-4';
                                                            @endphp
                                                            <i class="bi {{ $iconClass }}"></i>
                                                            <span>
                                                                {{ strlen($document->document_name) > 30 ? substr($document->document_name,0,30)."..." : $document->document_name; }}
                                                            </span>
                                                        </div>
                                                    
                                                    </td>
                                                    <td>{{ $document->upload_date }}</td>
                                                    <td>{{ $document->document_size }}</td>
                                                    <td class="text-center">{{ $document->document_extension }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                                            @if(in_array(strtolower($document->document_extension), ['jpg', 'jpeg', 'pdf']))
                                                                <a class="btn btn-primary btn-sm" 
                                                                    href="#" 
                                                                    title="View" 
                                                                    onclick="handlePreview(event, '{{ asset('storage/'. $document->document_path) }}', {{ $document->id }})">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                            @endif
                                                                
                                                            <a class="btn btn-success btn-sm" 
                                                                href="#" 
                                                                title="Download" 
                                                                onclick="handleDownload(event, {{ $document->id }})">
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center fw-bold">No Documents Found...</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div>
                                        {{ $documents->links('layouts.pagination') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>