<?php

namespace Task\Application\UseCases;

use Task\Application\DTOs\UpdateTaskDTO;
use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Domain\Entities\Task;

class UpdateTaskUseCase
{
    public function __construct(private TaskRepositoryInterface $repository) {}

    public function execute(UpdateTaskDTO $dto): Task
    {
        return $this->repository->update(new Task(
            id: $dto->id,
            title: $dto->title,
            description: $dto->description,
            due_time: $dto->due_time,
            status: $dto->status
        ));
    }
}
