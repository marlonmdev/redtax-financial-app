<?php

namespace App\Http\Controllers;

use App\Models\BlockTime;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlockTimeController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'asc');

        // Perform the search with Laravel Scout
        $query = BlockTime::search($search);

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
        $blockTimes = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );


        return view('block_times.index', [
            'blockTimes' => $blockTimes,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create()
    {
        return view('block_times.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'block_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'block_start_time' => ['required', 'before:block_end_time'],
            'block_end_time' => ['required', 'after:block_start_time']
        ]);

        // Check for overlapping block times
        $existingBlockTimes = BlockTime::where('block_date', $validated['block_date'])
            ->where(function ($query) use ($validated) {
                $query->where(function ($query) use ($validated) {
                    $query->where('block_start_time', '<', $validated['block_end_time'])
                        ->where('block_end_time', '>', $validated['block_start_time']);
                });
            })
            ->count();

        if ($existingBlockTimes > 0) {
            notify()->error('Block time overlaps with an existing block time', 'Error');
            return redirect()->route('block-times.create');
        }

        // Create new block time
        $blockTime = BlockTime::create([
            'block_date' => $validated['block_date'],
            'block_start_time' => $validated['block_start_time'],
            'block_end_time' => $validated['block_end_time']
        ]);

        if (!$blockTime) {
            notify()->error('Block Time Create Failed', 'Error');
            return redirect()->route('block-times.index');
        }

        notify()->success('Block Time Created Successfully', 'Success');
        return redirect()->route('block-times.index');
    }


    public function edit(BlockTime $blockTime)
    {
        return view('block_times.edit', compact('blockTime'));
    }

    public function update(BlockTime $blockTime, Request $request)
    {
        $validated = $request->validate([
            'block_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'block_start_time' => ['required', 'before:block_end_time'],
            'block_end_time' => ['required', 'after:block_start_time']
        ], [
            'block_date.unique' => 'This block date already exist, Please select another date.',
        ]);

        // Check for overlapping block times
        $existingBlockTimes = BlockTime::where('block_date', $validated['block_date'])
            ->where('id', '!=', $blockTime->id)
            ->where(function ($query) use ($validated) {
                $query->where(function ($query) use ($validated) {
                    $query->where('block_start_time', '<', $validated['block_end_time'])
                        ->where('block_end_time', '>', $validated['block_start_time']);
                });
            })
            ->count();

        if ($existingBlockTimes > 0) {
            notify()->error('Block Time overlaps with an existing block time', 'Error');
            return redirect()->route('block-times.edit', ['blockTime' => $blockTime->id]);
        }

        // Update block time
        if (!$blockTime->update($validated)) {
            notify()->error('Block Time Update Failed', 'Error');
            return redirect()->route('block-times.index');
        }

        notify()->success('Block Time Updated Successfully', 'Success');
        return redirect()->route('block-times.index');
    }

    public function destroy(BlockTime $blockTime)
    {
        if (!$blockTime->delete()) {
            notify()->error('Block Time Delete Failed', 'Error');
            return redirect()->route('block-times.index');
        }

        notify()->success('Block Time Deleted Successfully', 'Success');
        return redirect()->route('block-times.index');
    }
}
