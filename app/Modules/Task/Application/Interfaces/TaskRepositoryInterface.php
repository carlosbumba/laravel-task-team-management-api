<?php

namespace Task\Application\Interfaces;

use Task\Domain\Entities\Task;

interface TaskRepositoryInterface
{
    public function getAllByUserId(string $userId, ?string $status = null): array;
    public function getAllByTeamId(string $teamId, ?string $status = null): array;

    public function findById(string $id): Task;

    public function save(Task $task): Task;

    public function update(Task $task): Task;

    public function delete(string $id): void;
}
