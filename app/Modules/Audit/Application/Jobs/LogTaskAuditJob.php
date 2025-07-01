<?php

namespace Audit\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Auth\Infrastructure\Persistence\Model\User;
use Task\Infrastructure\Persistence\Model\Task;

class LogTaskAuditJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $action,
        protected User $user,
        protected Task $task,
        protected ?array $old = null
    ) {}

    public function handle(): void
    {
        $properties = [
            'action' => $this->action,
            'old' => $this->old,
            'new' => $this->action === 'deleted' ? null : $this->task->toArray(),
            'timestamp' => now(),
        ];

        activity('task')
            ->causedBy($this->user)
            ->performedOn($this->task)
            ->withProperties($properties)
            ->log("Tarefa {$this->action}");
    }
}
