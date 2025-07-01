<?php

use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Application\UseCases\DeleteTaskUseCase;

uses(Tests\TestCase::class);

it('deleta uma tarefa', function () {
    $taskId = 'Task Id';

    $repositoryMock = $this->mock(TaskRepositoryInterface::class);
    $repositoryMock->shouldReceive('delete')->with($taskId);

    $useCase = new DeleteTaskUseCase($repositoryMock);

    expect($useCase->execute($taskId))->toBeNull();
});
