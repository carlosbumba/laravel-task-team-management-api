<?php

namespace Task\Application\UseCases;

use Task\Application\DTOs\CreateTaskDTO;
use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Application\Interfaces\TaskAssignmentValidatorInterface;
use Task\Domain\Entities\Task;

class DelegateTaskUseCase
{
    public function __construct(
        private TaskRepositoryInterface $repository,
        private TaskAssignmentValidatorInterface $assignmentValidator
    ) {}

    public function execute(CreateTaskDTO $dto): Task
    {
        if ($dto->taskable_type === 'User') {
            if (!$this->assignmentValidator->canAssignToUser($dto->user_id, $dto->taskable_id)) {
                throw new \DomainException('You are not authorized to assign a task to this user.');
            }
        } elseif ($dto->taskable_type === 'Team') {
            if (! $this->assignmentValidator->canAssignToTeam($dto->user_id, $dto->taskable_id)) {
                throw new \DomainException('You are not authorized to assign a task to this team.');
            }
        } else {
            throw new \InvalidArgumentException('Invalid taskable_type. Must be User or Team.');
        }

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
