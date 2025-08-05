<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AllTasksController extends Controller
{
    public function index()
    {
        $tasks = Task::with('assignee', 'author')->latest()->get();

        return Inertia::render('AllTasks', [
            'authUser' => Auth::user(),
            'tasks' => $tasks,
        ]);
    }

    public function assign(Task $task)
    {
        if ($task->status === 'open') {
            $task->assignee_id = Auth::id();
            $task->status = 'assigned';
            $task->save();
        }

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'author_id' => Auth::id(),
        ]);

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $canDelete = ($task->author_id === Auth::id() && $task->status === 'open') 
                || ($task->author_id === Auth::id() && $task->status === 'assigned')
                || ($task->author_id === Auth::id() && $task->status === 'in_progress');

        if ($canDelete) {
            $task->delete();
        }

        return redirect()->back();
    }
    }
