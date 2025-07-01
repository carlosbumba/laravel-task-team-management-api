<?php

use Audit\Application\Services\TeamAuditLogger;
use Auth\Infrastructure\Persistence\Model\User;
use Team\Infrastructure\Persistence\Model\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('registra log de criação de equipe', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $logger = new TeamAuditLogger();
    $logger->logTeamCreated($user, $team);

    $log = Activity::latest()->first();

    expect($log)->not->toBeNull()
        ->and($log->log_name)->toBe('team')
        ->and($log->description)->toBe('Equipe criada')
        ->and($log->causer_id)->toBe($user->id)
        ->and($log->subject_id)->toBe($team->id)
        ->and($log->properties['action'])->toBe('criada')
        ->and($log->properties['old'])->toBeNull()
        ->and($log->properties['new']['id'])->toBe($team->id);
});

it('registra log de atualização de equipe', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['name' => 'Old Name']);
    $old = $team->toArray();

    $team->update(['name' => 'New Name']);

    $logger = new TeamAuditLogger();
    $logger->logTeamUpdated($user, $team, $old);

    $log = Activity::latest()->first();

    expect($log->description)->toBe('Equipe actualizada')
        ->and($log->properties['action'])->toBe('actualizada')
        ->and($log->properties['old']['name'])->toBe('Old Name')
        ->and($log->properties['new']['name'])->toBe('New Name');
});
