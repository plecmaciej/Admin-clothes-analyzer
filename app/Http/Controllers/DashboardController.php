<?php

namespace App\Http\Controllers;


use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $assignedTasks = Task::where('assignee_id', $user->id)
            ->orderBy('status')
            ->latest()
            ->get();

        return Inertia::render('Dashboard', [
            'assignedTasks' => $assignedTasks,
        ]);
    }
}