<?php

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Support\Facades\Gate;
use Shared\Domain\Enums\UserRole;
use Task\Application\UseCases\DelegateTaskUseCase;
use Task\Infrastructure\Persistence\Model\Task;
use Task\Domain\Entities\Task as TaskEntity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Task\Domain\Enums\TaskStatus;
use Team\Infrastructure\Persistence\Model\Team;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->route = route('api.v1.tasks.delegate');
    $this->taskableType = 'User';
    $this->taskableId = User::factory()->member()->create()->id;

    $this->user = User::factory()->admin()->create();
    Sanctum::actingAs($this->user);
});

it('permite que um admin delegue uma tarefa com sucesso', function () {
    Gate::shouldReceive('authorize')->with('delegate', Task::class)->andReturn(true);

    $status = TaskStatus::PENDING->value;

    $task = new TaskEntity(
        'task title',
        'task description',
        now()->addWeek(),
        $status,
        $this->taskableId,
        $this->taskableType
    );

    $useCase = $this->mock(DelegateTaskUseCase::class);
    $useCase->shouldReceive('execute')->once()->andReturn($task);

    $this->app->instance(DelegateTaskUseCase::class, $useCase);

    $this->postJson($this->route, [
        'title' => $task->title,
        'description' => $task->description,
        'due_time' => $task->due_time,
        'status' => $status,
        'taskable_type' => $this->taskableType,
        'taskable_id' => $this->taskableId,
    ])->assertCreated();
});

it('impede que um usuário "member" delegue uma tarefa para outro', function () {
    $this->user->role = UserRole::MEMBER;
    $this->user->save();

    $status = TaskStatus::PENDING->value;

    $task = new TaskEntity(
        'task title',
        'task description',
        now()->addWeek(),
        $status,
        $this->taskableId,
        $this->taskableType
    );

    $this->postJson($this->route, [
        'title' => $task->title,
        'description' => $task->description,
        'due_time' => $task->due_time,
        'status' => $status,
        'taskable_type' => $this->taskableType,
        'taskable_id' => $this->taskableId,
    ])->assertForbidden();
});

it('impede que um manager delegue tarefa para equipe que não gerencia', function () {
    $this->user->role = UserRole::MANAGER;
    $this->user->save();

    $this->taskableType = 'Team';
    $this->taskableId = Team::factory()->create()->id; // equipe sem vínculo

    $status = TaskStatus::PENDING->value;

    $task = new TaskEntity(
        'team task title',
        'team task description',
        now()->addWeek(),
        $status,
        $this->taskableId,
        $this->taskableType
    );

    $this->postJson($this->route, [
        'title' => $task->title,
        'description' => $task->description,
        'due_time' => $task->due_time,
        'status' => $status,
        'taskable_type' => $this->taskableType,
        'taskable_id' => $this->taskableId,
    ])->assertForbidden();
});
