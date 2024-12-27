<x-app-layout>
    <div class="page-header">
        <h3 class="fw-bold">Client Management</h3>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('clients.index') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <span>Clients</span>
            </li>
            <li class=" separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <span>Edit</span>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Client Profile</div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <form action="{{ route('clients.update', ['client' => $client->id ]) }}" method="post" class="col-8 offset-r-4">
                            @csrf
                            @method('put')

                            <div class="form-group ">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Client Name" value="{{ old('name', $client->name) }}" autofocus />
                                @error('name')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group ">
                                <label>Contact Details</label>
                                <input type="text" class="form-control" name="contact_details" placeholder="Enter Contact Details" value="{{ old('contact_details', $client->contact_details) }}" />
                                @error('contact_details')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group ">
                                <label>Tax Identification Number</label>
                                <input type="text" class="form-control" name="tax_identification_number" placeholder="Enter Tax Identification Number" value="{{ old('tax_identification_number', $client->tax_identification_number) }}" />
                                @error('tax_identification_number')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group ">
                                <label>Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Enter Client Address" value="{{ old('address', $client->address) }}" />
                                @error('address')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group ">
                                <label>Segment</label>
                                <input type="text" class="form-control" name="segment" placeholder="Enter Client Segment (e.g., individual, business)" value="{{ old('segment', $client->segment) }}" />
                                @error('segment')
                                <span class="text-danger">
                                    <p class="text-md fw-medium">{{ $message }}</p>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group d-flex gap-3">
                                <button type="submit" class="btn btn-success">UPDATE</button>

                                <a href="{{ route('clients.index') }}" class="btn btn-black">GO BACK</a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>