<?php

namespace Team\Application\UseCases;

use Team\Application\Interfaces\TeamRepositoryInterface;

class GetTeamByIdUseCase
{
    public function __construct(private TeamRepositoryInterface $repository) {}

    public function execute(string $id)
    {
        // outras operações ...
    }
}
