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
                        <ul class="d-flex justify-content-between nav nav-tabs nav-tabs-bordered mb-4">
                            <div class="d-flex">
                                <li class="nav-item">
                                    <a href="{{ route('clients.show-profile',  ['client' => $client->id]) }}" class="nav-link active">Profile</a>
                                </li>
                
                                <li class="nav-item">
                                    <a href="{{ route('clients.show-documents',  ['client' => $client->id]) }}" class="nav-link">Documents</a>
                                </li>
                            </div>
                            <div class="d-flex justify-content-end align-items-center">
                                <a href="{{ route('clients.edit', ['client' => $client->id]) }}" class="btn btn-success" title="Edit">Edit Profile</a>
                            </div>     
                        </ul>   
                        
                        <div class="tab-content pt-1">      
                            <!-- Client Profile -->
                            <div class="tab-pane fade show active" id="client-profile">

                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">Full Name</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                        <span>{{ $client->name === '' ? 'None' : $client->name }}</span>
                                    </div>
                                </div>
                                
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">Customer Type</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                        <span>{{ $client->customer_type === '' || $client->customer_type === null ? 'Not Set' : $client->customer_type }}</span>
                                    </div>
                                </div>
                
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">Business/Company</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                        <span>{{ $client->company === '' || $client->company === null ? 'None' : $client->company }}</span>
                                    </div>
                                </div>
                
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">Email</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                        <span>{{ $client->email ==='' ? 'None' : $client->email }}</span>
                                    </div>
                                </div>
                                
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">Phone Number</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                        <span>{{ $client->phone === '' ? 'None' : $client->phone }}</span>
                                    </div>
                                </div>
                                
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">Address</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                       <span>{{ $client->address === '' || $client->address === null ? 'Not Set' : $client->address }}</span>
                                    </div>
                                </div>
                                
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">City</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                       <span>{{ $client->city === '' || $client->city === null ? 'Not Set' : $client->city }}</span>
                                    </div>
                                </div>
                                
                                    
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">State</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                       <span>{{ $client->state === '' || $client->state === null ? 'Not Set' : $client->state }}</span>
                                    </div>
                                </div>
                                
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">Zip Code</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                       <span>{{ $client->zip_code === '' || $client->zip_code === null ? 'Not Set' : $client->zip_code }}</span>
                                    </div>
                                </div>
                                
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">Tax Identification Number</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                        <span>{{ $client->tax_identification_number === '' || $client->tax_identification_number === null ? 'None' :  $client->tax_identification_number }}</span>
                                    </div>
                                </div>
                                                                
                                <div class="row p-2">
                                    <div class="col-lg-3 col-md-4 fw-medium">Referred By</div>
                                    <div class="col-lg-9 col-md-8 border bg-light px-3 py-1 rounded">
                                        <span>{{ $client->referred_by === '' || $client->referred_by === null ? 'None' : $client->referred_by }}</span>
                                    </div>
                                </div>
                                
                                @if($client->client_selected_services->isNotEmpty())
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-hover table-sm">
                                            <thead>
                                                <th>Requested Service</th>
                                                <th>Details</th>
                                                <th>Date</th>
                                            </thead>
                                            <tbody>
                                                @forelse ($client->client_selected_services as $service)
                                                    <tr>
                                                        <td>{{ $service->services ? $service->services : '' }}</td>
                                                        <td>{{ $service->details ? $service->details : 'None' }}</td>
                                                        <td>{{ $service->created_at ? date('M d, Y g:i A', strtotime($service->created_at)) : '' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center fw-bold">No Records Found...</td>
                                                    </tr>
                                                @endforelse   
                                            </tbody>
                                        </table>
                                    </div>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>