<?php

namespace Audit\Application\Services;

use Audit\Application\Jobs\LogTaskAuditJob;
use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Task\Infrastructure\Persistence\Model\Task;

class TaskAuditLogger
{
    public function logTaskCreated(Authenticatable|User $user, Task $task): void
    {
        LogTaskAuditJob::dispatch('criada', $user, $task);
    }

    public function logTaskUpdated(Authenticatable|User $user, Task $task, array $oldData): void
    {
        LogTaskAuditJob::dispatch('actualizada', $user, $task, $oldData);
    }

    public function logTaskDeleted(Authenticatable|User $user, Task $task): void
    {
        LogTaskAuditJob::dispatch('deletada', $user, $task);
    }
}
