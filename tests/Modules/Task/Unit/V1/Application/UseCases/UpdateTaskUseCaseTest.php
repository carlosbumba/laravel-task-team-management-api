<?php

use Task\Application\DTOs\UpdateTaskDTO;
use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Application\UseCases\UpdateTaskUseCase;
use Task\Domain\Entities\Task;

uses(Tests\TestCase::class);

it('actualize os dados de uma tarefa', function () {
    $dto = new UpdateTaskDTO(
        'task id',
        'task title',
        'task description',
        now()->addWeek(),
        'pending'
    );

    $TaskEntity = new Task(
        id: $dto->id,
        title: $dto->title,
        description: $dto->description,
        due_time: $dto->due_time,
        status: $dto->status,
    );

    $updatedEntity = clone $TaskEntity;
    $updatedEntity->title = 'New Title';

    $repositoryMock = $this->mock(TaskRepositoryInterface::class);
    $repositoryMock->shouldReceive('update')->andReturn($updatedEntity);

    $useCase = new UpdateTaskUseCase($repositoryMock);
    $task = $useCase->execute($dto);

    expect($task->title)->toEqual($updatedEntity->title);
});
