<?php

namespace Task\Application\UseCases;

use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Domain\Entities\Task;

class GetTaskByIdUseCase
{
    public function __construct(private TaskRepositoryInterface $repository) {}

    public function execute(string $taskId): Task
    {
        return $this->repository->findById($taskId);
    }
}
