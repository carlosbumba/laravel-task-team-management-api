<?php

namespace Team\Application\UseCases;

use Team\Application\DTOs\TeamMemberDTO;
use Team\Application\Interfaces\TeamMemberRepositoryInterface;
use Team\Domain\Entities\TeamMember;

class AddTeamMemberUseCase
{
    public function __construct(private TeamMemberRepositoryInterface $repository) {}

    public function execute(TeamMemberDTO $dto): TeamMember
    {
        return $this->repository->addMember($dto->toEntity());
    }
}
