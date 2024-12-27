@if(in_array($document->document_extension, ['png', 'jpeg', 'jpg']))
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>
    <div class="text-center" style="background: #9740de;">
        <i class="bi bi-image card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@elseif($document->document_extension === 'doc')
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>
    <div class="text-center" style="background: #3FA2F6;">
        <i class="bi bi-filetype-doc card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@elseif($document->document_extension === 'docx')
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #3FA2F6;">
        <i class="bi bi-filetype-docx card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@elseif($document->document_extension === 'xls')
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #35a485;">
        <i class="bi bi-filetype-xls card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@elseif($document->document_extension === 'xlsx')
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #35a485;">
        <i class="bi bi-filetype-xlsx card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@elseif($document->document_extension === 'ppt')
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #EF5A6F;">
        <i class="bi bi-filetype-ppt card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@elseif($document->document_extension === 'pptx')
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #EF5A6F;">
        <i class="bi bi-filetype-pptx card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@elseif(in_array($document->document_extension, ['zip', 'rar']))
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #ddda40;">
        <i class="bi bi-file-zip card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@elseif(strtolower($document->document_extension) === 'pdf')
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #F5004F;">
        <i class="bi bi-file-pdf card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@elseif($document->document_extension === 'csv')
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #0ccf92;">
        <i class="bi bi-filetype-csv card-img-top" style="font-size: 8.8rem;"></i>
    </div>    
@elseif($document->document_extension === 'txt')
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #DEF9C4;">
        <i class="bi bi-filetype-txt card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@else
    <div class="position-relative">
        <div class="position-absolute top-0 end-0 bg-dark mt-2 me-2 px-2 py-1 rounded-pill" style="--bs-bg-opacity: .6;">
            <span class="text-light">{{ $document->document_size }}</span>
        </div>
    </div>

    <div class="text-center" style="background: #6482AD;">
        <i class="bi bi-file-earmark-text card-img-top" style="font-size: 8.8rem;"></i>
    </div>
@endif
