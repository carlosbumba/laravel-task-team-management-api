<?php

namespace Task\Application\UseCases;

use Task\Application\Interfaces\TaskRepositoryInterface;

class GetTasksByUserUseCase
{
    public function __construct(private TaskRepositoryInterface $repository) {}

    public function execute(string $userId, ?string $status = null)
    {
        return $this->repository->getAllByUserId($userId, $status);
    }
}
