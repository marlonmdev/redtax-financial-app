<?php

namespace App\Http\Controllers;

use App\Models\Blocked;
use App\Models\Message;
use App\Enums\ContactType;
use App\Enums\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class MessageController extends Controller
{

    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout
        $query = Message::search($search);

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
        $messages = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );


        return view('messages.index', [
            'messages' => $messages,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_type' => ['required', Rule::in(['Individual', 'Business'])],
            'name' => ['required'],
            'preferred_contact' => ['required', new Enum(ContactType::class)],
            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'email' => ['required', 'email', 'email:rfc,dns', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'subject' => ['required', new Enum(ServiceType::class)],
            'message' => ['required'],
        ]);

        // Check if the email, phone or name is blocked
        $isBlocked = Blocked::where('email', $validated['email'])
            ->orWhere('phone', $validated['phone'])
            ->orWhere('name', $validated['name'])
            ->exists();

        if ($isBlocked) {
            notify()->error('Your message could not be sent because your contact information is blocked.', 'Blocked');
            return redirect()->route('contact');
        }

        $message = new Message;
        $message->customer_type = $validated['customer_type'];
        $message->name = ucwords(strip_tags($validated['name']));
        $message->preferred_contact = $validated['preferred_contact'];
        $message->phone = strip_tags($validated['phone']) ?? null;
        $message->email = $validated['email'];
        $message->subject =  $validated['subject'];
        $message->message = strip_tags($validated['message']);

        if (!$message->save()) {
            notify()->error('Your message was not sent', 'Error');
            return redirect()->route('contact');
        }

        notify()->success('Your message was sent', 'Success');
        return redirect()->route('contact');
    }

    public function show(Message $message)
    {
        $message->viewed = 1;
        $message->save();
        return view('messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        if (!$message->delete()) {
            notify()->error('Message Deleted Failed', 'Error');
            return redirect()->route('messages.index');
        }

        notify()->success('Message Deleted Successfully', 'Success');
        return redirect()->route('messages.index');
    }

    public function block(Message $message)
    {
        // Store to blocked table
        $blocked = new Blocked;
        $blocked->name = $message->name;
        $blocked->phone = $message->phone;
        $blocked->email = $message->email;

        if (!$blocked->save()) {
            notify()->error('Contact block failed', 'Error');
            return redirect()->route('messages.index');
        }

        // delete the message after
        $message->delete();

        notify()->success('Contact blocked successfully', 'Success');
        return redirect()->route('messages.index');
    }
}
