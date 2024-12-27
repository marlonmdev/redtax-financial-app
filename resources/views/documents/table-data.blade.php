<div x-data="{ view: 'list' }">
    
    <div class="d-flex justify-content-between align-items-center flex-wrap py-3">   
        <div class="d-flex justify-content-end mb-3">
            <x-per-page-selector 
                route="documents.index" 
                :sort-field="request('sort_field', 'id')" 
                :sort-direction="request('sort_direction', 'desc')" 
                :search="$search"
                :per-page="$perPage" 
            />      
            
            <button @click="view = 'list'" :class="view === 'list' ? 'btn-dark' : 'btn-light'" class="btn ms-4 me-2">
                <i class="bi bi-list-task"></i>
            </button>
            <button @click="view = 'grid'" :class="view === 'grid' ? 'btn-dark' : 'btn-light'" class="btn">
                <i class="bi bi-grid-fill"></i>
            </button>
        </div>
        
        <div class="d-flex gap-2">
            <a hx-get="{{ route('documents.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat me-1"></i></a>
            <x-search-form :route="route('documents.index')" :search="$search"></x-search-form>
            <input type="hidden" id="per-page" value="{{ $perPage }}">
        </div>
    </div>
         
    <div x-show="view === 'list'" x-cloak>
        @include('documents.list-view')
    </div>

    <div x-show="view === 'grid'" x-cloak>
        @include('documents.grid-view')
    </div>
</div>
