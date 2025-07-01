<?php

namespace Audit\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Auth\Infrastructure\Persistence\Model\User;
use Team\Infrastructure\Persistence\Model\Team;

class LogTeamAuditJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $action,
        protected User $user,
        protected Team $team,
        protected ?array $old = null
    ) {}

    public function handle(): void
    {
        $properties = [
            'action' => $this->action,
            'old' => $this->old,
            'new' => $this->action === 'deleted' ? null : $this->team->toArray(),
            'timestamp' => now(),
        ];

        activity('team')
            ->causedBy($this->user)
            ->performedOn($this->team)
            ->withProperties($properties)
            ->log("Equipe {$this->action}");
    }
}
