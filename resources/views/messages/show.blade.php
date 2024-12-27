<x-app-layout>
    <div class="pagetitle">
        <h1>Communication Tools</h1>
        <p class="text-dark"> <a href="{{ route('messages.index') }}">Messages</a> <i class="bi bi-caret-right-fill"></i> Show</p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                
                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('messages.index') }}" class="btn btn-dark btn-sm">Go Back</a>
                    
                    <form hx-post="{{ route('messages.block', ['message' => $message->id]) }}" hx-target="body">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure to block this contact?')" class="btn btn-danger btn-sm" title="Block Contact">Block Contact</button>
                    </form>
                </div>
                
                <div class="card px-4 py-5">
                    <div class="card-body">
                       
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-1">
                            <div class="d-flex d-sm-inline-flex flex-wrap mb-1">
                                <div class="me-4">
                                    <label>From: </label> <span class="fw-medium">{{ $message->name }}</span>
                                </div>
                                <div>
                                    <label>Customer Type: </label> <span class="fw-medium">{{ $message->customer_type }}</span>
                                </div>
                            </div> 
                            
                            <div>
                                <label>Sent On: </label> <span class="fw-medium">{{ date('F d, Y h:i a', strtotime($message->created_at)) }}</span>
                            </div> 
                        </div>
                        
                        
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-1">
                            <div class="d-flex d-sm-inline-flex align-items-center flex-wrap mb-1">
                                <div class="me-4">
                                    <label>Email: </label> <span class="fw-medium">{{ $message->email }}</span>
                                </div> 
                                
                                @if($message->phone)
                                    <div class="me-4">
                                        <label>Phone: </label> <span class="fw-medium">{{ $message->phone }}</span>
                                    </div>
                                @endif
                            </div> 
                            
                            <div>
                                <label>Preferred contact via: </label> <span class="fw-medium">{{ $message->preferred_contact }}</span>
                            </div> 
                        </div>
                        
                        
                        <div class="mb-2">
                            <label>Subject: </label> <span class="fw-medium">{{ $message->subject }}</span>
                        </div>
                                                
                        <div class="border bg-light p-3 rounded-md">
                            {{ $message->message }}
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>