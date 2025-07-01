<?php

namespace Task\Application\Mappers;

use Task\Domain\Entities\Task as TaskEntity;
use Task\Infrastructure\Persistence\Model\Task as ModelTask;

class TaskMapper
{
    public static function fromModel(ModelTask $model): TaskEntity
    {
        return new TaskEntity(
            id: $model->id,
            title: $model->title,
            description: $model->description,
            due_time: $model->due_time,
            status: $model->status,
            taskable_id: $model->taskable_id,
            taskable_type: $model->taskable_type,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
        );
    }
}
