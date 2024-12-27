<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Task;
use App\Models\User;
use App\Enums\RoleType;
use App\Enums\StatusType;
use App\Enums\PriorityType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\TaskRequest;
use Illuminate\Validation\Rules\Enum;

class TaskController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');
        $filterNotes = request('filter_notes', false);

        // Perform the search with Laravel Scout
        $query = Task::search($search)->query(function ($builder) use ($filterNotes) {
            // Add withCount to include the notes count
            $builder->withCount('notes');

            // Apply the note filter only if the button was clicked (i.e., filter_notes is true)
            if ($filterNotes) {
                $builder->whereHas('notes', function ($query) {
                    $query->where('message_to', auth()->id());
                });
                // If the user is a 'Client', only show their own tasks
            } else if (auth()->user()->role->role_name === 'Client' || auth()->user()->role->role_name === 'Staff') {
                $builder->where('user_id', auth()->id());
            }
        });


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
        $tasks = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );

        $taskCountsQuery = Task::selectRaw("
            COUNT(CASE WHEN status = 'Not Started' THEN 1 END) as not_started_tasks,
            COUNT(CASE WHEN status LIKE '%Pending%' THEN 1 END) as pending_tasks,
            COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as in_progress_tasks,
            COUNT(CASE WHEN status LIKE '%Completed%' THEN 1 END) as completed_tasks
        ");

        // If the authenticated user is a 'Client' or 'Staff', filter the counts by user_id
        if (auth()->user()->role->role_name === 'Client' || auth()->user()->role->role_name === 'Staff') {
            $taskCountsQuery->where('user_id', auth()->id());
        }

        $taskCounts = $taskCountsQuery->first();

        $count = [
            'not-started-tasks' => $taskCounts->not_started_tasks,
            'pending-tasks' => $taskCounts->pending_tasks,
            'in-progress-tasks' => $taskCounts->in_progress_tasks,
            'completed-tasks' => $taskCounts->completed_tasks,
        ];

        $statusTypes = StatusType::cases();
        $taskNotesCount = Note::where('message_to', auth()->id())->count();

        return view('tasks.index', [
            'tasks' => $tasks,
            'taskNotesCount' => $taskNotesCount,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'count' => $count,
            'statusTypes' => $statusTypes
        ]);
    }

    public function create()
    {
        $priorityTypes = PriorityType::cases();
        return view('tasks.create', compact('priorityTypes'));
    }

    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $task = new Task;
        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->status = StatusType::NOT_STARTED->value;
        $task->priority = $validated['priority'];
        $task->due_date = $validated['due_date'];
        $task->assigned_by = auth()->user()->name;
        $task->assigned_to = $validated['assign_to'];
        $task->client_id = null;
        $task->document_id = null;
        $task->user_id = $validated['assign_to_id'];
        $task->assigned_agent_id = null;
        if (!$task->save()) {
            notify()->error('Task Creation Failed', 'Error');
            return redirect()->route('tasks.create');
        }

        notify()->success('Task Created Successfully');
        return redirect()->route('tasks.create');
    }

    public function preview(Task $task)
    {
        $users = User::all();
        $task->load('notes');
        return view('tasks.preview', compact('task', 'users'));
    }

    public function notes(Task $task)
    {
        $users = User::all();
        $task->load('notes');
        return view('tasks.task-notes', compact('task', 'users'));
    }

    public function edit(Task $task)
    {
        $priorityTypes = PriorityType::cases();
        $users = User::all();
        $task->load('notes');
        return view('tasks.edit', compact('task', 'priorityTypes', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:5'],
            'description' => ['required', 'min:5'],
            'priority' => ['required', new Enum(PriorityType::class)],
            'due_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:' . $task->due_date],
            'assign_to' => ['required'],
        ]);

        // Use only if task should be assign to a registered user
        // $validated = $request->validate([
        //     'title' => ['required', 'min:5'],
        //     'description' => ['required', 'min:5'],
        //     'priority' => ['required', new Enum(PriorityType::class)],
        //     'due_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:' . $task->due_date],
        //     'assign_to' => ['required'],
        //     'assign_to_id' => ['required'],
        // ], [
        //     'assign_to_id.required' => 'You must assign this task to a registered user.',
        // ]);

        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->priority = $validated['priority'];
        $task->due_date = $validated['due_date'];
        $task->assigned_to = $validated['assign_to'];
        $task->user_id = $request->assign_to_id === '' || $request->assign_to_id === null ? $task->user_id : $request->assign_to_id;

        if (!$task->save()) {
            notify()->error('Task Update Failed', 'Error');
            return redirect()->route('tasks.edit', ['task' => $task->id]);
        }

        notify()->success('Task Updated Successfully', 'Success');
        return redirect()->route('tasks.edit', ['task' => $task->id]);
    }

    public function updateStatus(Task $task, Request $request)
    {
        $validated = $request->validate([
            'status' => ['required']
        ]);

        $updated = $task->update([
            'status' => $validated['status']
        ]);

        if (!$updated) {
            notify()->error('Task Status Update Failed', 'Error');
            return redirect()->route('tasks.index');
        }

        notify()->success('Task Status Updated Successfully', 'Success');
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        if (!$task->delete()) {
            notify()->error('Task Deleted Failed', 'Error');
            return redirect()->route('tasks.index');
        }

        notify()->success('Task Deleted Successfully', 'Success');
        return redirect()->route('tasks.index');
    }
}
