<div class="d-flex justify-content-between align-items-center flex-wrap py-3">
    <x-per-page-selector 
        route="messages.index" 
        :sort-field="request('sort_field', 'id')" 
        :sort-direction="request('sort_direction', 'desc')" 
        :search="$search"
        :per-page="$perPage" 
    />      
    
    <div class="d-flex gap-2">
        <a hx-get="{{ route('messages.index') }}" hx-target="body" class="btn btn-dark" data-bs-toggle="tooltip" title="Refresh Table"><i class="bi bi-arrow-repeat"></i></a>
        <x-search-form :route="route('messages.index')" :search="$search"></x-search-form>
        <input type="hidden" id="per-page" value="{{ $perPage }}">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover" >
        <thead> 
            <tr>                       
                <x-sortable-header 
                    field="name" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="messages.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    From
                </x-sortable-header>
                
                
                <x-sortable-header 
                    field="email" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="messages.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Email
                </x-sortable-header>
                
                <x-sortable-header 
                    field="subject" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="messages.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Subject
                </x-sortable-header>
                
                <x-sortable-header 
                    field="created_at" 
                    :current-field="request('sort_field')" 
                    :current-direction="request('sort_direction')" 
                    route="messages.index" 
                    :search="$search"
                    :per-page="$perPage"
                    >
                    Sent On
                </x-sortable-header>
                
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        
        <tbody id="table-data">
            @forelse($messages as $message)
                <tr>
                    @php
                        $textClass =  $message->viewed === 0 ? 'fw-medium' : '';
                    @endphp
                    <td>
                        <div class="d-flex align-items-center gap-2">  
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&background=random" alt="sender avatar" width="24" height="auto" class="rounded-circle">
                            <span class="{{ $textClass }}">{{ $message->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="{{ $textClass}}">{{ $message->email }}</span>
                    </td>
                    <td>
                        <span class="{{ $textClass}}">{{ $message->subject }}</span>
                    </td>
                    <td>
                        <span class="{{ $textClass}}">{{ date('M d, Y g:i A', strtotime($message->created_at)) }}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">    
                            <a href="{{ route('messages.show', ['message' => $message->id]) }}" class="btn btn-primary btn-sm" title="View Message"><i class="bi bi-eye-fill"></i></a>
                            
                            <form hx-post="{{ route('messages.block', ['message' => $message->id]) }}" hx-target="body">
                                @csrf
                                <button type="submit" onclick="return confirm('Are you sure to block this contact?')" class="btn btn-warning btn-sm" title="Block Contact"><i class='bi bi-ban'></i></button>
                            </form>
                            
                            <form hx-post="{{ route('messages.destroy', ['message' => $message->id]) }}" hx-target="body">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are you sure to delete this message?')" class="btn btn-danger btn-sm" title="Delete"><i class='bi bi-trash-fill'></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center fw-bold">No Records Found...</td>
                </tr>
            @endforelse
        </tbody>  
    </table>
    <div>
        {{ $messages->appends([
            'search' => $search,
            'sort_field' => request('sort_field', 'id'),
            'sort_direction' => request('sort_direction', 'desc'),
            'per_page' => $perPage
        ])->links('layouts.pagination') }}
    </div>
</div>