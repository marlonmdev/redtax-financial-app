
@forelse($client->client_documents as $document)    
    <table class="table table-responsive table-hover">
        <thead>
            <th>Name</th>
            <th>Uploaded On</th>
            <th>Size</th>
            <th class="text-center">Type</th>
            <th class="text-center">Actions</th>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="d-flex justify-content-start align-items-center gap-2">
                        @if(in_array($document->document_extension, ['png', 'jpeg', 'jpg']))
                        <i class="bi bi-image text-success fs-4"></i>
                        @elseif(in_array($document->document_extension, ['pdf', 'PDF']))
                            <i class="bi bi-filetype-pdf text-danger fs-4"></i>
                        @elseif($document->document_extension === 'doc')
                            <i class="bi bi-filetype-doc text-primary fs-4"></i>
                        @elseif($document->document_extension === 'docx')
                            <i class="bi bi-filetype-docx text-primary fs-4"></i>
                        @elseif($document->document_extension === 'xls')
                            <i class="bi bi-filetype-xls text-success fs-4"></i>
                        @elseif($document->document_extension === 'xlsx')
                            <i class="bi bi-filetype-xlsx text-success fs-4"></i>
                        @elseif($document->document_extension === 'csv')
                            <i class="bi bi-filetype-csv text-success fs-4"></i>
                        @endif
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
                        
                        @if(in_array($document->document_extension, ['jpg', 'jpeg', 'JPG', 'JPEG']))
                            <a class="btn btn-primary btn-sm" data-fslightbox="gallery" href="{{ asset('storage/'. $document->document_path) }}" data-bs-toggle="tooltip" data-bs-title="View Image">
                                <i class="bi bi-eye"></i>
                            </a>
                        @elseif($document->document_extension === 'pdf')
                            <a class="btn btn-primary btn-sm"  href="/storage/{{ $document->document_path }}" target="_blank" data-bs-toggle="tooltip" data-bs-title="View Document"><i class="bi bi-eye"></i></a>
                        @endif
                        
                        <form action="{{ route('documents.download', ['document' => $document->id ]) }}" method="get">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-title="Download Document"><i class='bi bi-download'></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
@empty
    @include('layouts.no-data', [$type = 'Documents'])
@endforelse