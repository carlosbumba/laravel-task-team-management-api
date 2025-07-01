<?php

namespace Task\Application\UseCases;

use Task\Infrastructure\Repositories\EloquentTaskRepository;

class GetTeamTasksUseCase
{
    public function __construct(private EloquentTaskRepository $repository) {}

    public function execute(string $type, string $teamId, ?string $status = null): array
    {
        return $this->repository->getByTaskable($type, $teamId, $status);
    }
}
