<?php

namespace Team\Application\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Team\Domain\Entities\Team as TeamEntity;
use Team\Domain\Entities\TeamMember as TeamMemberEntity;

interface TeamRepositoryInterface
{
    public function create(TeamEntity $team): TeamEntity;

    public function findAll(): array;

    public function findByUserId(string $userId): array;

    public function update(TeamEntity $team): TeamEntity;

    public function delete(string $id): void;
}
