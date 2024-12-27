<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => ['required'],
            'message_by' => ['required'],
            'message_to' => ['required'],
            'note' => ['required'],
        ]);

        $created = Note::create($validated);

        if (!$created) {
            notify()->error('Note Save Failed', 'Error');
            return redirect()->route('tasks.edit', ['task' => $validated['task_id']]);
        }

        notify()->success('Note Saved Successfully');
        return back();
    }


    public function update(Note $note, Request $request)
    {
        $validated = $request->validate([
            'message_to' => ['required'],
            'note' => ['required']
        ]);

        $updated = $note->update($validated);

        if (!$updated) {
            notify()->error('Note Update Failed', 'Error');
            return back();
        }

        notify()->success('Note Updated Successfully');
        return back();
    }

    public function destroy(Note $note)
    {
        if (!$note->delete()) {
            notify()->error('Note delete failed', 'Error');
            return back();
        }

        notify()->success('Note deleted successfully', 'Success');
        return back();
    }
}
