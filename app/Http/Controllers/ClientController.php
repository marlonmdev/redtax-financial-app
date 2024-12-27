<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $search = trim(request('search')) ?? '';
        $perPage = 15;
        if ($search === "") {
            $clients = Client::orderBy('id', 'desc')->paginate($perPage);
        } else {
            $clients = Client::search($search)
                ->orderBy('id', 'desc')
                ->paginate($perPage)
                ->appends([
                    'search' => $search,
                ]);
        }

        return view('clients.index', ['clients' => $clients, 'search' => $search, 'perPage' => $perPage]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();

        $validated['name'] = ucfirst(strip_tags($validated['name']));
        $validated['contact_details'] = strip_tags($validated['contact_details']);
        $validated['address'] = ucfirst(strip_tags($validated['address']));
        $validated['tax_identification_number'] = strip_tags($validated['tax_identification_number']);
        $validated['segment'] = ucfirst(strip_tags($validated['segment']));
        $validated['assigned_agent_id'] = null;

        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }


    public function edit(Client $client)
    {
        return view('clients.edit', ['client' => $client]);
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $validated = $request->validated();

        $client['name'] = ucfirst(strip_tags($validated['name']));
        $client['contact_details'] = strip_tags($validated['contact_details']);
        $client['address'] = ucfirst(strip_tags($validated['address']));
        $client['tax_identification_number'] = strip_tags($validated['tax_identification_number']);
        $client['segment'] = ucfirst(strip_tags($validated['segment']));
        $client['assigned_agent_id'] = null;

        $client->save();

        return redirect()->route('clients.edit', ['client' => $client->id])->with('success', 'Client profile updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
