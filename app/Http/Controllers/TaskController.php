<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['author', 'assignee'])->latest()->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Task::create([
            ...$validated,
            'author_id' => Auth::id(),
        ]);

        return redirect()->back();
    }


    public function assign(Task $task)
    {
        if ($task->assignee_id) {
            return back()->withErrors('Zadanie już ma przypisanego użytkownika.');
        }

        $task->update([
            'assignee_id' => Auth::id(),
            'status' => 'assigned',
        ]);

        return back()->with('success', 'Zadanie przypisane do Ciebie.');
    }


    public function update(Request $request, Task $task)
    {
        if ($task->assignee_id !== Auth::id()) {
            abort(403, 'Brak uprawnień do edycji zadania.');
        }

        $validated = $request->validate([
            'status' => 'in:open,assigned,in_progress,completed,cancelled',
            'type' => 'nullable|in:one_time,periodic',
            'target_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'frequency' => 'nullable|string',
        ]);

        $task->update($validated);

        return back()->with('success', 'Zadanie zaktualizowane.');
    }

    public function destroy(Task $task)
    {
        if ($task->author_id !== Auth::id()) {
            abort(403, 'Tylko autor może usunąć zadanie.');
        }

        $task->delete();

        return back()->with('success', 'Zadanie usunięte.');
    }
}
