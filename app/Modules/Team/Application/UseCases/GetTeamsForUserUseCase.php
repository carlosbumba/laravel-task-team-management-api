<?php

namespace Team\Application\UseCases;

use Team\Application\Interfaces\TeamRepositoryInterface;

class GetTeamsForUserUseCase
{
    public function __construct(private TeamRepositoryInterface $repository) {}

    public function execute(string $userId): array
    {
        return $this->repository->findByUserId($userId);
    }
}
