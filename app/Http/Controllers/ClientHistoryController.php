<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\ClientHistory;

class ClientHistoryController extends Controller
{
    public function index()
    {
        $search = trim(request('search')) ?? '';
        $perPage = 15;
        if ($search === "") {
            $histories = ClientHistory::orderBy('id', 'desc')->paginate($perPage);
        } else {
            $histories = ClientHistory::search($search)
                ->orderBy('id', 'desc')
                ->paginate($perPage)
                ->appends([
                    'search' => $search,
                ]);;
        }

        return view('client_history.index', ['histories' => $histories, 'search' => $search]);
    }

    public function show(ClientHistory $history)
    {
        $client_id = $history->client_id;
        $client_histories = Client::find($client_id)->client_histories();
        return view('client_history.show', ['client_histories' => $client_histories]);
    }
}
