<?php

namespace Task\Application\Interfaces;

interface TaskAssignmentValidatorInterface
{
    public function canAssignToUser(string $delegatorId, string $targetUserId): bool;

    public function canAssignToTeam(string $delegatorId, string $teamId): bool;
}

