<?php

use Task\Application\DTOs\CreateOwnTaskDTO;
use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Application\UseCases\CreateOwnTaskUseCase;
use Task\Domain\Entities\Task;

uses(Tests\TestCase::class);

it('registra um tarefa pessoal do usuário', function () {
    $dto = new CreateOwnTaskDTO('task title', 'task description', now()->addWeek(), 'pending', 'user_id', 'User');

    $expectedTask = new Task(
        id: 'Task Id',
        title: $dto->title,
        description: $dto->description,
        due_time: $dto->due_time,
        status: $dto->status,
        taskable_type: $dto->taskable_type,
        taskable_id: $dto->taskable_id,
    );

    $mock = $this->mock(TaskRepositoryInterface::class);
    $mock->shouldReceive('save')->andReturn($expectedTask);

    $useCase = new CreateOwnTaskUseCase($mock);
    $task = $useCase->execute($dto);

    expect($task->id)->toEqual($expectedTask->id);
});
