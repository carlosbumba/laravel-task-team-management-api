<?php

namespace Team\Application\UseCases;

use Team\Application\Interfaces\TeamMemberRepositoryInterface;

class RemoveTeamMemberUseCase
{
    public function __construct(private TeamMemberRepositoryInterface $repository) {}

    public function execute(string $teamId, string $userId)
    {
        return $this->repository->removeMember($teamId, $userId);
    }
}
