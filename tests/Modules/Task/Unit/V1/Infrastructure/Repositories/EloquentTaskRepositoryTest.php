<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Task\Infrastructure\Repositories\EloquentTaskRepository;
use Task\Domain\Entities\Task as TaskEntity;
use Task\Infrastructure\Persistence\Model\Task as EloquentTask;
use Task\Domain\Enums\TaskStatus;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new EloquentTaskRepository();
    $this->taskableType = 'User';
    $this->taskableId = (string) Str::ulid();
});


it('saves a new task', function () {
    $task = new TaskEntity(
        id: null,
        title: 'Test Task',
        description: 'Descrição',
        due_time: now()->addDay(),
        status: TaskStatus::PENDING->value,
        taskable_type: $this->taskableType,
        taskable_id: $this->taskableId
    );

    $saved = $this->repository->save($task);

    expect($saved->id)->not()->toBeNull();
    expect($saved->title)->toBe($task->title);
});


it('updates a task', function () {
    $model = EloquentTask::factory()->create([
        'title' => 'Original',
        'taskable_type' => $this->taskableType,
        'taskable_id' => $this->taskableId,
    ]);

    $task = new TaskEntity(
        id: $model->id,
        title: 'Atualizado',
        description: $model->description,
        due_time: $model->due_time,
        status: $model->status,
        taskable_type: $model->taskable_type,
        taskable_id: $model->taskable_id,
        created_at: $model->created_at,
        updated_at: $model->updated_at,
    );

    $updated = $this->repository->update($task);

    expect($updated->title)->toBe('Atualizado');
});

it('finds a task by id', function () {
    $model = EloquentTask::factory()->create([
        'taskable_type' => $this->taskableType,
        'taskable_id' => $this->taskableId,
    ]);

    $found = $this->repository->findById($model->id);

    expect($found->id)->toBe($model->id);
});

it('gets all tasks by user id', function () {
    EloquentTask::factory()->count(2)->create([
        'taskable_type' => $this->taskableType,
        'taskable_id' => $this->taskableId,
    ]);

    $tasks = $this->repository->getAllByUserId($this->taskableId);

    expect($tasks)->toHaveCount(2);
});

it('filters tasks by status', function () {
    EloquentTask::factory()->create([
        'taskable_type' => $this->taskableType,
        'taskable_id' => $this->taskableId,
        'status' => TaskStatus::PENDING->value,
    ]);

    EloquentTask::factory()->create([
        'taskable_type' => $this->taskableType,
        'taskable_id' => $this->taskableId,
        'status' => TaskStatus::COMPLETED->value,
    ]);

    $pending = $this->repository->getAllByUserId($this->taskableId, TaskStatus::PENDING->value);

    expect($pending)->toHaveCount(1);
    expect($pending[0]->status)->toBe(TaskStatus::PENDING->value);
});

it('deletes a task', function () {
    $model = EloquentTask::factory()->create();

    $this->repository->delete($model->id);

    $this->repository->findById($model->id);
})->throws(ModelNotFoundException::class);
