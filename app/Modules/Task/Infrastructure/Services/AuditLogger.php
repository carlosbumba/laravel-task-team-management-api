<?php

namespace Task\Infrastructure\Services;

use Auth\Infrastructure\Persistence\Model\User;
use Task\Infrastructure\Persistence\Model\Task;

class AuditLogger
{
    public function logTaskUpdate(User $user, Task $task, array $oldData)
    {
        activity('task')
            ->causedBy($user)
            ->performedOn($task)
            ->withProperties([
                'old' => $oldData,
                'new' => $task->toArray()
            ])
            ->log('Tarefa atualizada');
    }
}
