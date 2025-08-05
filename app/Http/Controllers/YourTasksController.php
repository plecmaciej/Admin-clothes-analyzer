<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class YourTasksController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tasks = Task::with('author')
                    ->where('assignee_id', $user->id)
                    ->get();

        return Inertia::render('YourTasks', [
            'assignedTasks' => $tasks,
            'authUser' => Auth::user(),
        ]);
    }

    public function update(Request $request, Task $task)
    {
        if ($task->assignee_id !== Auth::id()) {
            abort(403, 'Brak dostępu do tego zadania');
        }

        $data = $request->validate([
            'type' => 'required|string|in:one_time,periodic',
            'target_url' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'frequency' => 'nullable|string',
        ]);


        $task->update(array_merge($data, [
            'status' => 'in_progress',
        ]));

        return redirect()->back();
    }

    public function destroy(Task $task)
    {

        //if (!in_array($task->status, ['assigned', 'cancelled'])) {
        //    abort(403, 'Można usuwać tylko zadania w statusie "assigned" lub "cancelled"');
        //}

        $task->delete();

        return redirect()->back();
    }
}