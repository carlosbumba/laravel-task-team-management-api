<?php

use Task\Application\DTOs\CreateTaskDTO;
use Task\Application\Interfaces\TaskAssignmentValidatorInterface;
use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Application\UseCases\DelegateTaskUseCase;
use Task\Domain\Entities\Task;

uses(Tests\TestCase::class);

function getCreateTaskDTOHelper(
    $title = null,
    $description = null,
    $due_time = null,
    $status = null,
    $user_id = null,
    $taskable_id = null,
    $taskable_type = null
): CreateTaskDTO {
    return new CreateTaskDTO(
        $title ?? 'task title',
        $description ?? 'task description',
        $due_time ?? now()->addWeek(),
        $status ?? 'pending',
        $user_id ?? 'user_id',
        $taskable_id ?? 'User Id',
        $taskable_type ?? 'User'
    );
}

it('delega tarefa a um usuário', function () {
    $dto  = getCreateTaskDTOHelper();

    $expectedTask = new Task(
        id: 5910,
        title: $dto->title,
        description: $dto->description,
        due_time: $dto->due_time,
        status: $dto->status,
        taskable_type: $dto->taskable_type,
        taskable_id: $dto->taskable_id,
    );

    $repositoryMock = $this->mock(TaskRepositoryInterface::class);
    $repositoryMock->shouldReceive('save')->andReturn($expectedTask);

    $taskAssigmentValidatorMock = $this->mock(TaskAssignmentValidatorInterface::class);
    $taskAssigmentValidatorMock->shouldReceive('canAssignToUser')->with($dto->user_id, $dto->taskable_id)->andReturn(true);

    $useCase = new DelegateTaskUseCase($repositoryMock, $taskAssigmentValidatorMock);
    $task = $useCase->execute($dto);

    expect($task->id)->toEqual($expectedTask->id);
});


it('delega tarefa a uma equipe', function () {
    $dto  = getCreateTaskDTOHelper(taskable_id: 'Team Id', taskable_type: 'Team');

    $expectedTask = new Task(
        id: 6931,
        title: $dto->title,
        description: $dto->description,
        due_time: $dto->due_time,
        status: $dto->status,
        taskable_type: $dto->taskable_type,
        taskable_id: $dto->taskable_id,
    );

    $repositoryMock = $this->mock(TaskRepositoryInterface::class);
    $repositoryMock->shouldReceive('save')->andReturn($expectedTask);

    $taskAssigmentValidatorMock = $this->mock(TaskAssignmentValidatorInterface::class);
    $taskAssigmentValidatorMock->shouldReceive('canAssignToTeam')->with($dto->user_id, $dto->taskable_id)->andReturn(true);

    $useCase = new DelegateTaskUseCase($repositoryMock, $taskAssigmentValidatorMock);
    $task = $useCase->execute($dto);

    expect($task->id)->toEqual($expectedTask->id);
});


it('lança exceção ao usar taskable_type invalido', function () {
    $dto  = getCreateTaskDTOHelper(taskable_id: 'Group Id', taskable_type: 'Group');

    $expectedTask = new Task(
        id: 6931,
        title: $dto->title,
        description: $dto->description,
        due_time: $dto->due_time,
        status: $dto->status,
        taskable_type: $dto->taskable_type,
        taskable_id: $dto->taskable_id,
    );

    $repositoryMock = $this->mock(TaskRepositoryInterface::class);
    $repositoryMock->shouldReceive('save')->andReturn($expectedTask);

    $taskAssigmentValidatorMock = $this->mock(TaskAssignmentValidatorInterface::class);
    $taskAssigmentValidatorMock->shouldReceive('canAssignToTeam')->with($dto->user_id, $dto->taskable_id);

    $useCase = new DelegateTaskUseCase($repositoryMock, $taskAssigmentValidatorMock);
    $useCase->execute($dto);
})->throws(InvalidArgumentException::class);
