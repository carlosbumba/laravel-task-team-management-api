<?php

namespace Task\Infrastructure\Services;

use Auth\Infrastructure\Persistence\Model\User;
use Team\Infrastructure\Persistence\Model\Team;
use Task\Application\Interfaces\TaskAssignmentValidatorInterface;

class TaskAssignmentValidator implements TaskAssignmentValidatorInterface
{
    public function canAssignToUser(string $delegatorId, string $targetUserId): bool
    {
        $delegator = User::findOrFail($delegatorId);

        // Pode atribuir a si mesmo ou se for admin, pode a qualquer um
        return $delegatorId === $targetUserId || $delegator->hasAnyRole('admin');
    }

    public function canAssignToTeam(string $delegatorId, string $teamId): bool
    {
        $delegator = User::findOrFail($delegatorId);
        $team = Team::with(['members'])->findOrFail($teamId);

        // Admin pode tudo
        if ($delegator->hasAnyRole('admin')) {
            return true;
        }

        // Verifica se é manager da equipe
        return $team->members()
            ->where('user_id', $delegatorId)
            ->where('role_in_team', 'manager')
            ->exists();
    }
}
