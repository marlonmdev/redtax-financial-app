<div class="card">
    <div class="card-header">
        <div class="card-title">Client History</div>
    </div>



    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Client Name</th>
                    <th scope="col">Activity</th>
                    <th scope="col">Date</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="table-data">
                @forelse($histories as $history)
                <tr>
                    <td>{{ $history->id }}</td>
                    <td>{{ $history->client_id }}</td>
                    <td>{{ $history->activity }}</td>
                    <td>{{ $history->date }}</td>
                    <td class="d-flex justify-content-center gap-2">
                        <a href="{{ route('history.show', ['history' => $history->id]) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-title="View"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center fw-bold">No Records Found...</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div>

    {{ $histories->links() }}

</div>