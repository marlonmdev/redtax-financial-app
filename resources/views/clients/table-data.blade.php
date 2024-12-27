<div class="card">
    <div class="card-header">
        <div class="card-title">Client Profiles</div>
    </div>



    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Contact Details</th>
                    <th scope="col">Tax Identification Number</th>
                    <th scope="col">Segment</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="table-data">
                @forelse($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->contact_details }}</td>
                    <td>{{ $client->tax_identification_number }}</td>
                    <td>{{ $client->segment }}</td>
                    <td class="d-flex justify-content-center gap-2">
                        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-title="View"><i class="fas fa-eye"></i></a>

                        <a href="{{ route('clients.edit', ['client' => $client->id]) }}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-pen"></i></a>

                        <form action="{{ route('clients.destroy', ['client' => $client->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="return confirm('Are you sure to delete Client?')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-title="Delete"><i class='fas fa-trash-alt'></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center fw-bold">No Data Found...</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div>

    {{ $clients->links() }}

</div>