<?php

namespace Team\Application\UseCases;

use Team\Application\Interfaces\TeamMemberRepositoryInterface;

class GetTeamMembersUseCase
{
    public function __construct(private TeamMemberRepositoryInterface $repository) {}

    public function execute(string $teamId)
    {
        return $this->repository->getMembers($teamId);
    }
}
