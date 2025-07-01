<?php

namespace Team\Application\Mappers;

use Team\Domain\Entities\Team as TeamEntity;
use Team\Infrastructure\Persistence\Model\Team as TeamModel;

class TeamMapper
{
    public static function fromModel(TeamModel $model): TeamEntity
    {
        return new TeamEntity(
            id: $model->id,
            name: $model->name,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }
}
