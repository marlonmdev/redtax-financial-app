<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Client;
use App\Models\AuditLog;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Mail\AccountCreatedEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout
        $query = Client::search($search);

        // Get all results matching the search query
        $allResults = $query->get();

        // Apply sorting based on field type
        $sortedResults = $allResults->sortBy(function ($user) use ($sortField, $sortDirection) {
            $value = $user->{$sortField};

            // Determine if the value is numeric
            if (is_numeric($value)) {
                $value = (int) $value; // Ensure value is treated as integer
            }

            return $value;
        }, SORT_REGULAR, $sortDirection === 'desc');


        // Paginate the sorted results manually
        $currentPage = $page;
        $results = $sortedResults->forPage($currentPage, $perPage);

        // Create a LengthAwarePaginator instance
        $clients = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );

        // Load relationships
        $clients->load('user');

        return view('clients.index', [
            'clients' => $clients,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function showProfile(Client $client)
    {
        $client->load('client_selected_services');
        return view('clients.show-profile', compact('client'));
    }

    public function showDocuments(Client $client)
    {
        $client_id = $client->id;
        $client_name = $client->name;
        $search = trim(request('search')) ?? '';
        $perPage = 15;
        if ($search === "") {
            $documents = Document::where('client_id', $client->id)->orderBy('id', 'desc')->paginate($perPage);
        } else {
            $documents = Document::search($search)
                ->where('client_id', $client->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage)
                ->appends([
                    'search' => $search,
                ]);
        }

        return view('clients.show-documents', compact('client_id', 'client_name', 'documents', 'search', 'perPage'));
    }

    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();

        $client = new Client;
        $client->name = ucwords(strip_tags($validated['name']));
        $client->customer_type = $validated['customer_type'];
        $client->company = $validated['customer_type'] === 'Business' ? strip_tags($validated['company']) : null;
        $client->email = $validated['email'];
        $client->phone = $validated['phone'];
        $client->preferred_contact = $validated['preferred_contact'];
        $client->address = strip_tags($validated['address']);
        $client->city = strip_tags($validated['city']);
        $client->state = strip_tags($validated['state']);
        $client->zip_code = strip_tags($validated['zip_code']);
        $client->tax_identification_number = strip_tags($validated['tax_identification_number']);
        $client->referred_by = strip_tags($validated['referred_by']);
        $client->assigned_agent_id = null;

        if (!$client->save()) {
            notify()->error('Client Profile Save Failed', 'Error');
            return redirect()->route('clients.index');
        }

        notify()->success('Client Profile Saved Successfully', 'Success');
        return redirect()->route('clients.index');
    }


    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $validated = $request->validated();

        $updated = $client->update([
            'name' => ucwords(strip_tags($validated['name'])),
            'customer_type' => $validated['customer_type'],
            'company' => $validated['customer_type'] === 'Business' ? strip_tags($validated['company']) : null,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'preferred_contact' => $validated['preferred_contact'],
            'address' => strip_tags($validated['address']),
            'city' => strip_tags($validated['city']),
            'state' => strip_tags($validated['state']),
            'zip_code' => strip_tags($validated['zip_code']),
            'tax_identification_number' => strip_tags($validated['tax_identification_number']) ?? 'null',
            'referred_by' => strip_tags($validated['referred_by'])
        ]);

        if (!$updated) {
            notify()->error('Client Profile Update Failed', 'Error');
            return redirect()->route('clients.edit', ['client' => $client->id]);
        }

        notify()->success('Client Profile Updated Successfully', 'Success');
        return redirect()->route('clients.edit', ['client' => $client->id]);
    }

    public function destroy(Client $client)
    {
        $clientName = $client->name;

        if (!$client->delete()) {
            notify()->error('Client Delete Failed', 'Error');
            return redirect()->route('clients.index');
        }

        // Save the delete log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Deleted a client with the name " . $clientName;
        $auditLog->save();

        notify()->success('Client Deleted Successfully', 'Success');
        return redirect()->route('clients.index');
    }

    public function createUserAccount(Client $client)
    {
        $existingUserEmail = DB::table('users')
            ->where('email', $client->email)
            ->first();

        if ($existingUserEmail) {
            notify()->error('Client\'s email already exist in user accounts', 'Error');
            return redirect()->route('clients.index');
        }

        $user = new User;
        $user->name = $client->name;
        $user->email = $client->email;
        $user->password = Hash::make(Str::random(8));
        $user->role_id = Role::where('role_name', 'Client')->value('id');
        $user->has_access = 0;

        if (!$user->save()) {
            notify()->error('Client Account Creation Failed', 'Error');
            return redirect()->route('clients.index');
        }

        DB::table('clients')->where('id', $client->id)->update(['user_id' => $user->id]);

        $details = [
            'name' => $client->name,
            'email' => $client->email,
        ];

        // Send email after
        Mail::to($client->email)->send(new AccountCreatedEmail($details));

        notify()->success('Client Account Created Successfully', 'Success');
        return redirect()->route('clients.index');
    }
}
