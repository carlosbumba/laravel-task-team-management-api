<?php

namespace Task\Infrastructure\Repositories;

use Auth\Infrastructure\Persistence\Model\User;
use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Application\Mappers\TaskMapper;
use Task\Domain\Entities\Task as TaskEntity;
use Task\Domain\Enums\TaskStatus;
use Task\Infrastructure\Persistence\Model\Task as EloquentTask;
use Team\Infrastructure\Persistence\Model\Team;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function getAllByUserId(string $userId, ?string $status = null): array
    {
        $query = EloquentTask::where('taskable_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get()->map(fn($task) => TaskMapper::fromModel($task))->all();
    }

    public function getAllByTeamId(string $teamId, ?string $status = null): array
    {
        $query = EloquentTask::where('taskable_id', $teamId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get()->map(fn($task) => TaskMapper::fromModel($task))->all();
    }

    public function findById(string $id): TaskEntity
    {
        $task = EloquentTask::findOrFail($id);
        return TaskMapper::fromModel($task);
    }

    public function getByTaskable(string $type, string $id, ?string $status = null): array
    {
        $builder = EloquentTask::where('taskable_type', $type)->where('taskable_id', $id);

        if ($status) {
            $builder->where('status', $status);
        }

        return $builder->get()->map(fn($task) => TaskMapper::fromModel($task))->all();
    }

    public function save(TaskEntity $task): TaskEntity
    {
        if (!str_contains($task->taskable_type, '\\')) {
            $taskable_type = match ($task->taskable_type) {
                'User' => User::class,
                'Team' => Team::class,
                default => throw new \InvalidArgumentException('Invalid taskable type')
            };
        }

        $attributes = [
            'title'       => $task->title,
            'description' => $task->description,
            'due_time'    => $task->due_time,
            'taskable_type' => $taskable_type ?? $task->taskable_type,
            'taskable_id' => $task->taskable_id,
        ];

        $attributes['status'] = $task->status ?? TaskStatus::PENDING->value;

        $model = EloquentTask::create($attributes);

        return TaskMapper::fromModel($model);
    }

    public function update(TaskEntity $task): TaskEntity
    {
        $model = EloquentTask::findOrFail($task->id);

        $model->update([
            'title'       => $task->title,
            'description' => $task->description,
            'due_time'    => $task->due_time,
            'status'      => $task->status,
        ]);

        return TaskMapper::fromModel($model);
    }

    public function delete(string $id): void
    {
        $model = EloquentTask::findOrFail($id);
        $model->delete();
    }
}
