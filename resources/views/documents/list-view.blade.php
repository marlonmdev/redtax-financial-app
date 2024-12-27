<div class="table-responsive">
    <table class="table table-hover" >
        <thead> 
            <tr>                      
                <x-sortable-header 
                    field="document_name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="documents.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Document Name
                </x-sortable-header>
                
                <x-sortable-header 
                    field="uploaded_by" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="documents.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Uploaded By
                </x-sortable-header>
                
                <x-sortable-header 
                    field="upload_date" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="documents.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Uploaded On
                </x-sortable-header>
                
                <th>Size</th>
                
                <th>Status</th>
                
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($documents as $document)
                <tr>
                    <td>
                        @php
                            $icons = [
                                'png' => 'bi-image text-success',
                                'jpeg' => 'bi-image text-success',
                                'jpg' => 'bi-image text-success',
                                'pdf' => 'bi-filetype-pdf text-danger',
                                'doc' => 'bi-filetype-doc text-primary',
                                'docx' => 'bi-filetype-docx text-primary',
                                'xls' => 'bi-filetype-xls text-success',
                                'xlsx' => 'bi-filetype-xlsx text-success',
                                'csv' => 'bi-filetype-csv text-success',
                                'zip' => 'bi-file-zip-fill text-warning',
                            ];

                            $iconClass = $icons[strtolower($document->document_extension)] ?? 'bi-file-earmark';
                        @endphp
                        <i class="bi {{ $iconClass }}"></i>
                        <span>{{ strlen($document->document_name) > 20 ? substr($document->document_name,0,20)."..." : $document->document_name; }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">  
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($document->uploaded_by) }}&background=random" alt="user avatar" width="24" height="auto" class="rounded-circle">
                        <span>{{ $document->uploaded_by }}</span>
                        </div>
                    </td>
                    <td>{{ date('m/d/Y g:i A', strtotime($document->upload_date)) }}</td>
                    <td>{{ $document->document_size }}</td>
                    <td>
                        @if($document->viewed === 1 && $document->downloaded === 1)
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge text-bg-primary">Viewed</span>
                                <span class="badge text-bg-success">Downloaded</span>
                            </div>
                        @elseif($document->viewed === 1)
                            <span class="badge text-bg-primary">Viewed</span>
                        @elseif($document->downloaded === 1)
                            <span class="badge text-bg-success">Downloaded</span>
                        @else
                            <span class="badge text-bg-dark">None</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-2">
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

                            @can('delete', $document)
                                <form hx-post="{{ route('documents.destroy', ['document' => $document->id]) }}" hx-target="body">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return confirm('Are you sure to delete Document?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center fw-bold">No Records Found...</td>
                </tr>
            @endforelse
        </tbody>  
    </table>
    <div>
        {{ $documents->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>