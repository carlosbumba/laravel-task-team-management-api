<?php

namespace Task\Application\UseCases;

use Task\Application\DTOs\CreateOwnTaskDTO;
use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Domain\Entities\Task;

class CreateOwnTaskUseCase
{
    public function __construct(
        private TaskRepositoryInterface $repository
    ) {}

    public function execute(CreateOwnTaskDTO $dto): Task
    {
        return $this->repository->save(new Task(
            id: null,
            title: $dto->title,
            description: $dto->description,
            due_time: $dto->due_time,
            status: $dto->status,
            taskable_type: $dto->taskable_type,
            taskable_id: $dto->taskable_id,
        ));
    }
}
