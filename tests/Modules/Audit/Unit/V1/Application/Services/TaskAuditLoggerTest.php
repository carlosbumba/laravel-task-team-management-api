<?php

use Audit\Application\Jobs\LogTaskAuditJob;
use Audit\Application\Services\TaskAuditLogger;
use Auth\Infrastructure\Persistence\Model\User;
use Task\Infrastructure\Persistence\Model\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Spatie\Activitylog\Models\Activity;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('registra log de criação de tarefa', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $logger = new TaskAuditLogger();
    $logger->logTaskCreated($user, $task);

    $log = Activity::latest()->first();

    expect($log)->not->toBeNull()
        ->and($log->log_name)->toBe('task')
        ->and($log->description)->toBe('Tarefa criada')
        ->and($log->causer_id)->toBe($user->id)
        ->and($log->subject_id)->toBe($task->id)
        ->and($log->properties['action'])->toBe('criada')
        ->and($log->properties['old'])->toBeNull()
        ->and($log->properties['new']['id'])->toBe($task->id);
});

it('registra log de atualização de tarefa', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['title' => 'Old']);
    $old = $task->toArray();

    $task->update(['title' => 'New']);

    $logger = new TaskAuditLogger();
    $logger->logTaskUpdated($user, $task, $old);

    $log = Activity::latest()->first();

    expect($log->description)->toBe('Tarefa actualizada')
        ->and($log->properties['action'])->toBe('actualizada')
        ->and($log->properties['old']['title'])->toBe('Old')
        ->and($log->properties['new']['title'])->toBe('New');
});
