<?php

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Task\Application\UseCases\GetTasksByUserUseCase;
use Task\Application\UseCases\CreateOwnTaskUseCase;
use Task\Application\UseCases\GetTaskByIdUseCase;
use Task\Application\UseCases\UpdateTaskUseCase;
use Task\Application\UseCases\DeleteTaskUseCase;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;
use Task\Application\Mappers\TaskMapper;
use Task\Domain\Entities\Task as TaskEntity;
use Task\Domain\Enums\TaskStatus;
use Task\Infrastructure\Persistence\Model\Task;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->resource_route_prefix = 'api.v1.tasks.';
    $this->user = User::factory()->member()->create();

    Sanctum::actingAs($this->user);
});

it('retorna tarefas do usuário com status válido', function () {
    $status = TaskStatus::PENDING->value;

    $useCase = $this->mock(GetTasksByUserUseCase::class);
    $useCase->shouldReceive('execute')
        ->with($this->user->id, $status)
        ->andReturn(collect());

    $this->app->instance(GetTasksByUserUseCase::class, $useCase);

    $this->getJson(route("{$this->resource_route_prefix}index", ['status' => $status]))
        ->assertOk();
});

it('retorna erro de validação ao fornecer status inválido', function () {
    $this->getJson(route("{$this->resource_route_prefix}index", ['status' => 'invalid-status']))
        ->assertUnprocessable();
});

it('cria uma nova tarefa com dados válidos', function () {
    $task = new TaskEntity('task title', 'task description', now()->addWeek(), 'pending', 'user_id', 'User');

    $useCase = $this->mock(CreateOwnTaskUseCase::class);
    $useCase->shouldReceive('execute')->andReturn($task);

    $this->app->instance(CreateOwnTaskUseCase::class, $useCase);

    $this->postJson(route($this->resource_route_prefix . 'store'), [
        'title' => 'Nova tarefa',
        'description' => 'Descrição',
        'due_time' => now()->addDay()->toDateString(),
        'status' => TaskStatus::PENDING->value,
        'taskable_type' => 'User',
        'taskable_id' => $this->user->id,
    ])->assertCreated();
});

it('exibe detalhes da tarefa quando autorizado', function () {
    $task = Task::factory()->create(['taskable_type' => 'User', 'taskable_id' => $this->user->id]);

    Gate::shouldReceive('authorize')
        ->with('view', Mockery::on(fn($arg) => $arg instanceof Task))
        ->andReturn(true);

    $useCase = $this->mock(GetTaskByIdUseCase::class);
    $useCase->shouldReceive('execute')
        ->with($task->id)
        ->andReturn(TaskMapper::fromModel($task));

    $this->app->instance(GetTaskByIdUseCase::class, $useCase);

    $this->getJson(route($this->resource_route_prefix . 'show', $task))
        ->assertOk();
});

it('atualiza uma tarefa quando autorizado', function () {
    $task = Task::factory()->create(['taskable_type' => 'User', 'taskable_id' => $this->user->id]);

    Gate::shouldReceive('authorize')
        ->with('update', Mockery::on(fn($arg) => $arg instanceof Task))
        ->andReturn(true);

    $useCase = $this->mock(UpdateTaskUseCase::class);
    $useCase->shouldReceive('execute')->andReturn(TaskMapper::fromModel($task));

    $this->app->instance(UpdateTaskUseCase::class, $useCase);

    $this->putJson(route($this->resource_route_prefix . 'update', $task), [
        'title' => 'Atualizada',
        'description' => 'Nova desc',
        'status' => TaskStatus::IN_PROGRESS->value,
    ])->assertOk();
});

it('remove uma tarefa quando autorizado', function () {
    $task = Task::factory()->create();

    Gate::shouldReceive('authorize')
        ->with('delete', Mockery::on(fn($arg) => $arg instanceof Task))
        ->andReturn(true);

    $useCase = $this->mock(DeleteTaskUseCase::class);
    $useCase->shouldReceive('execute')->with($task->id);

    $this->app->instance(DeleteTaskUseCase::class, $useCase);

    $this->deleteJson(route($this->resource_route_prefix . 'destroy', $task))
        ->assertNoContent();
});
