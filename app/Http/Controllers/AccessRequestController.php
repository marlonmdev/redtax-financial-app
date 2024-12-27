<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Models\AccessRequest;
use App\Mail\AccessRequestEmail;
use Illuminate\Support\Facades\Mail;

class AccessRequestController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout and load note counts
        $query = AccessRequest::search($search);

        // Get all results matching the search query
        $allResults = $query->get();

        // Apply sorting based on field type
        $sortedResults = $allResults->sortBy(function ($task) use ($sortField, $sortDirection) {
            $value = $task->{$sortField};

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
        $accessRequests = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );

        return view('access_requests.index', [
            'accessRequests' => $accessRequests,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function grantAccess(User $user)
    {
        $granted = $user->update([
            'has_access' => 1,
            'access_granted_at' => now()
        ]);

        if (!$granted) {
            notify()->error('User Access Request Grant Failed', 'Error');
            return redirect()->route('access-requests.index');
        }

        $details = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        // Send email if granted
        Mail::to($user->email)->send(new AccessRequestEmail($details));

        $accessRequest = AccessRequest::where('user_id', $user->id)->first();
        $accessRequest->delete();

        // Save the action log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Granted login access to client named " . $user->name;
        $auditLog->save();

        notify()->success('User Access Request Granted, Access Duration is Within 24 Hours', 'Success');
        return redirect()->route('access-requests.index');
    }

    public function denyAccess(User $user)
    {
        $user->update([
            'has_access' => 0,
        ]);

        $accessRequest = AccessRequest::where('user_id', $user->id)->first();
        $accessRequest->delete();

        notify()->success('User Access Request Denied', 'Success');
        return redirect()->route('access-requests.index');
    }
}
