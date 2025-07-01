<?php

namespace Team\Application\Mappers;

use Team\Domain\Entities\TeamMember as TeamMemberEntity;
use Team\Infrastructure\Persistence\Model\TeamMember as TeamMember;

class TeamMemberMapper
{
    public static function fromModel(TeamMember $model): TeamMemberEntity
    {
        return new TeamMemberEntity(
            id: $model->id,
            team_id: $model->team_id,
            user_id: $model->user_id,
            role_in_team: $model->role_in_team,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }
}
