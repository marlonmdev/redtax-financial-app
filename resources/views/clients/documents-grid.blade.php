
@forelse($client->client_documents as $document)
    @php
        $extension = pathinfo($document->document_name, PATHINFO_EXTENSION);   
    @endphp
    
    <div class="card p-1" style="width: 18rem;">
        @include('documents.by-extension')
        <div class="d-flex flex-wrap">
            <span class="fw-medium text-dark">
                {{ strlen($document->document_name) > 30 ? substr($document->document_name,0,30)."..." : $document->document_name; }}
            </span>
        </div>
        <div>    
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center gap-2">  
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($document->client->name) }}&background=random" alt="Uploader Avatar" width="30" height="auto" class="rounded-circle">
                    <div class="d-flex flex-column">
                        <small class="text-dark fw-bold">by {{ $document->client->name }}</small>
                        <small class="text-dark">{{ date('F d, Y', strtotime($document->upload_date)) }}</small>
                    </div>
                </div>
                <div class="dropdown" data-bs-toggle="tooltip" data-bs-title="Click to show options">
                    <i class="bi bi-three-dots-vertical fs-5" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"  href="/storage/{{ $document->document_path }}" target="_blank">Open</a></li>
                        <li>
                            <form action="{{ route('documents.download', ['document' => $document->id ]) }}" method="get">
                                @csrf
                                <button type="submit" class="dropdown-item">Download</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div> 
        </div>
    </div>  
@empty
    @include('layouts.no-data', [$type = 'Documents'])
@endforelse