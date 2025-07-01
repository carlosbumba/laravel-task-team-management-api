<?php

namespace Task\Application\UseCases;

use Task\Application\Interfaces\TaskRepositoryInterface;

class DeleteTaskUseCase
{
    public function __construct(private TaskRepositoryInterface $repository) {}

    public function execute(string $id): void
    {
        $this->repository->delete($id);
    }
}
