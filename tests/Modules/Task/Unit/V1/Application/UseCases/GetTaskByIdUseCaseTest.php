<?php

use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Application\UseCases\GetTaskByIdUseCase;
use Task\Domain\Entities\Task;

uses(Tests\TestCase::class);

it('retorna uma tarefa pela id', function () {
    $taskId = 'Task Id';
    $expectedTask = new Task('task title', 'task description', now()->addWeek(), 'pending', 'user_id', 'User', $taskId);

    $repositoryMock = $this->mock(TaskRepositoryInterface::class);
    $repositoryMock->shouldReceive('findById')->with($taskId)->andReturn($expectedTask);

    $useCase = new GetTaskByIdUseCase($repositoryMock);
    $task = $useCase->execute($taskId);

    expect($task->id)->toEqual($expectedTask->id);
});
