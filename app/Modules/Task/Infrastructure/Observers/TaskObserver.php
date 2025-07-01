<?php

namespace Task\Infrastructure\Observers;

use Audit\Application\Services\TaskAuditLogger;
use Task\Infrastructure\Persistence\Model\Task;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    public function __construct(protected TaskAuditLogger $logger) {}

    public function created(Task $task): void
    {
        $user = Auth::user();
        if ($user) {
            $this->logger->logTaskCreated($user, $task);
        }
    }

    public function updated(Task $task): void
    {
        $user = Auth::user();
        if ($user) {
            $old = $task->getOriginal();
            $this->logger->logTaskUpdated($user, $task, $old);
        }
    }

    public function deleted(Task $task): void
    {
        $user = Auth::user();
        if ($user) {
            $this->logger->logTaskDeleted($user, $task);
        }
    }
}
