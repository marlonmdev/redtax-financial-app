<div class="table-responsive">
    <div class="grid-view-container">
        @forelse($documents as $document)   
            <div class="card p-1" style="width: 15rem;">
                @include('documents.by-extension')
                <div class="d-flex flex-wrap">
                    <span class="fw-medium text-dark">
                        {{ strlen($document->document_name) > 22 ? substr($document->document_name, 0, 22)."..." : $document->document_name; }}
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
                                @if($document->document_extension === 'pdf')
                                    <li>
                                        <a  class="dropdown-item"  href="{{ asset('storage/' . $document->document_path) }}" target="_blank">View PDF</a>  
                                    </li>
                                @elseif(in_array($document->document_extension, ['jpg', 'jpeg', 'png']))
                                    <li>
                                        <a class="dropdown-item" data-fslightbox="gallery" href="{{ asset('storage/'. $document->document_path) }}">
                                            View Image
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <form action="{{ route('documents.download', ['document' => $document->id ]) }}" method="get">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Download</button>
                                    </form>
                                </li>
                                <li>
                                    <form hx-post="{{ route('documents.destroy', ['document' => $document->id]) }}" hx-target="body">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" onclick="return confirm('Are you sure to delete Document?')" class="dropdown-item">
                                            Delete
                                        </button>
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
    </div>
    <div>
        {{ $documents->links('layouts.pagination') }}
    </div>
</div>
