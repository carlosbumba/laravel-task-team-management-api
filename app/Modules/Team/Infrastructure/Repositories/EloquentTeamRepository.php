<?php

namespace Team\Infrastructure\Repositories;

use Team\Application\Interfaces\TeamRepositoryInterface;
use Team\Application\Mappers\TeamMapper;
use Team\Domain\Entities\Team as TeamEntity;
use Team\Infrastructure\Persistence\Model\Team as EloquentTeam;

class EloquentTeamRepository implements TeamRepositoryInterface
{

    public function findAll(): array
    {
        return EloquentTeam::all()->map(fn($team) => TeamMapper::fromModel($team))->all();
    }

    public function findByUserId(string $userId): array
    {
        return EloquentTeam::whereHas('members', fn($q) => $q->where('user_id', $userId))
            ->get()
            ->map(fn($team) => TeamMapper::fromModel($team))
            ->all();
    }

    public function create(TeamEntity $team): TeamEntity
    {
        $team = EloquentTeam::create(['name' => $team->name]);

        return TeamMapper::fromModel($team);
    }

    public function update(TeamEntity $team): TeamEntity
    {
        $model = EloquentTeam::findOrFail($team->id);
        $model->update(['name' => $team->name]);

        return TeamMapper::fromModel($model);
    }

    public function delete(string $id): void
    {
        EloquentTeam::findOrFail($id)->delete();
    }
}
