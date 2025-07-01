<?php

namespace Team\Application\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Team\Domain\Entities\TeamMember as TeamMemberEntity;

interface TeamMemberRepositoryInterface
{
    public function getMembers(string $teamId): Collection;

    public function addMember(TeamMemberEntity $teamMember): TeamMemberEntity;

    public function removeMember(string $teamId, string $userId): void;
}
