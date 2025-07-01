<?php

namespace Team\Infrastructure\Observers;

use Team\Infrastructure\Persistence\Model\Team;
use Audit\Application\Services\TeamAuditLogger;
use Illuminate\Support\Facades\Auth;

class TeamObserver
{
    public function __construct(protected TeamAuditLogger $logger) {}

    public function created(Team $team): void
    {
        $user = Auth::user();

        if ($user) {
            $this->logger->logTeamCreated($user, $team);
        }
    }

    public function updated(Team $team): void
    {
        $user = Auth::user();

        if ($user) {
            $old = $team->getOriginal();
            $this->logger->logTeamUpdated($user, $team, $old);
        }
    }

    public function deleted(Team $team): void
    {
        $user = Auth::user();

        if ($user) {
            $this->logger->logTeamDeleted($user, $team);
        }
    }
}
