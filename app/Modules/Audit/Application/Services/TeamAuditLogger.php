<?php

namespace Audit\Application\Services;

use Audit\Application\Jobs\LogTeamAuditJob;
use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Team\Infrastructure\Persistence\Model\Team;

class TeamAuditLogger
{
    public function logTeamCreated(Authenticatable|User $user, Team $team): void
    {
        LogTeamAuditJob::dispatch('criada', $user, $team);
    }

    public function logTeamUpdated(Authenticatable|User $user, Team $team, array $old): void
    {
        LogTeamAuditJob::dispatch('actualizada', $user, $team, $old);
    }

    public function logTeamDeleted(Authenticatable|User $user, Team $team): void
    {
        LogTeamAuditJob::dispatch('deletada', $user, $team);
    }
}

