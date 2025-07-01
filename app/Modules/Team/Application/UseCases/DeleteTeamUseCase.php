<?php

namespace Team\Application\UseCases;

use Team\Application\Interfaces\TeamRepositoryInterface;

class DeleteTeamUseCase
{
    public function __construct(private TeamRepositoryInterface $repository) {}

    public function execute(string $teamId): void
    {
        $this->repository->delete($teamId);
    }
}
