<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout
        $query = AuditLog::search($search);

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
        $audit_logs = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );

        // Load relationships
        $audit_logs->load('user');

        return view('audit_logs.index', [
            'audit_logs' => $audit_logs,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function clearAuditLogs()
    {
        DB::table('audit_logs')->truncate();

        notify()->success('Audit Logs Cleared Successfully', 'Success');
        return redirect()->route('audit-logs.index');
    }
}
