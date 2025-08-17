<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskStatusService;

class TestScraperController extends Controller
{
    public function test(TaskStatusService $service)
    {
        set_time_limit(0);
        $task = new Task([
            'id' => 9999, // fikcyjne ID
            'title' => 'Testowy katalog koszulek',
            'target_url' => 'https://www.adidas.pl/kobiety-koszulki',
        ]);

        $service->handleInProgressStatus($task);

        return response()->json([
            'status' => 'Scraper uruchomiony',
            'task' => $task->only(['title', 'target_url']),
        ]);
    }
}
