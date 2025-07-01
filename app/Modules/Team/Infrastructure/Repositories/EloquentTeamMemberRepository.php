<?php

namespace Team\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Team\Application\Interfaces\TeamMemberRepositoryInterface;
use Team\Application\Mappers\TeamMemberMapper;
use Team\Domain\Entities\TeamMember as TeamMemberEntity;
use Team\Exceptions\DuplicateTeamMemberException;
use Team\Exceptions\InvalidTeamMemberException;
use Team\Infrastructure\Persistence\Model\Team as EloquentTeam;

class EloquentTeamMemberRepository implements TeamMemberRepositoryInterface
{

    // membros
    public function getMembers(string $teamId): Collection
    {
        $team = EloquentTeam::findOrFail($teamId);
        return $team->users;
    }

    public function addMember(TeamMemberEntity $teamMember): TeamMemberEntity
    {
        $team = EloquentTeam::findOrFail($teamMember->team_id);

        if ($team->members()->where('user_id', $teamMember->user_id)->exists()) {
            throw new DuplicateTeamMemberException('The selected user is already in team.');
        }

        $model = $team->members()->create(['user_id' => $teamMember->user_id, 'role_in_team' => $teamMember->role_in_team]);

        return TeamMemberMapper::fromModel($model);
    }

    public function removeMember(string $teamId, string $userId): void
    {
        $team = EloquentTeam::findOrFail($teamId);

        if (!$team->members()->where('user_id', $userId)->exists()) {
            throw new InvalidTeamMemberException('The selected user is not on the team.');
        }

        $team->members()->where('user_id', $userId)->delete();
    }
}
