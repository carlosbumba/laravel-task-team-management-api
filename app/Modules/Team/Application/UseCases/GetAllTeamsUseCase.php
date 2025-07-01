<?php

namespace Team\Application\UseCases;

use Team\Application\Interfaces\TeamRepositoryInterface;

class GetAllTeamsUseCase
{
    public function __construct(private TeamRepositoryInterface $repository) {}

    public function execute()
    {
        return $this->repository->findAll();
    }
}
