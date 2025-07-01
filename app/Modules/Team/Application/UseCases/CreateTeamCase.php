<?php

namespace Team\Application\UseCases;

use Team\Application\DTOs\TeamDTO;
use Team\Application\Interfaces\TeamRepositoryInterface;
use Team\Domain\Entities\Team;

class CreateTeamCase
{
    public function __construct(private TeamRepositoryInterface $repository) {}

    public function execute(TeamDTO $dto): Team
    {
        return $this->repository->create($dto->toEntity());
    }
}
