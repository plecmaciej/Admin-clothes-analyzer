<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\TaskStatusService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $taskId;

    /**
     * Tworzymy job z ID taska.
     */
    public function __construct(int $taskId)
    {
        \Log::info("Konstruktor joba, taskId = {$taskId}");
        $this->taskId = $taskId;
    }

    /**
     * Obsługa joba.
     */
    public function handle(TaskStatusService $service): void
    {
        \Log::info("Handle joba, taskId = {$this->taskId}");
        
        // Pobieramy taska świeżo z bazy
        $task = Task::find($this->taskId);

        if (! $task) {
            Log::warning("Zadanie o ID {$this->taskId} nie istnieje.");
            return;
        }

        Log::info("Rozpoczęto zadanie {$task->title} (ID: {$task->id})");

        // Wywołanie serwisu na aktualnym obiekcie
        try {
            $service->handleInProgressStatus($task);
        } catch (\Throwable $e) {
            Log::error("Błąd podczas handleInProgressStatus dla task ID {$task->id}: " . $e->getMessage());
            return;
        }

        // Ustawienie statusu completed
        $task->update(['status' => 'completed']);

        Log::info("Zakończono zadanie {$task->title} (ID: {$task->id})");
    }
}
