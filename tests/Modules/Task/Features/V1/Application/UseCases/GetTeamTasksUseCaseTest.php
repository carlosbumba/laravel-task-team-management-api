<?php

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Team\Infrastructure\Persistence\Model\Team;
use Task\Infrastructure\Persistence\Model\Task;
use Laravel\Sanctum\Sanctum;
use Shared\Domain\Enums\UserRole;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->endpoint = 'api.v1.team.tasks';

    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

it('permite que membros da equipe vejam tarefas da equipe', function () {
    $team = Team::factory()->hasAttached($this->user, ['role_in_team' => 'member'])->create();

    Task::factory()->count(2)->create([
        'taskable_type' => Team::class,
        'taskable_id' => $team->id,
    ]);

    $response = $this->getJson(route($this->endpoint, ['team' => $team->id]));

    $response->assertOk()->assertJsonCount(2, 'data');
});

it('nega acesso para usuários "member" não membros da equipe', function () {
    $this->user->role = UserRole::MEMBER;
    $this->user->save();

    $team = Team::factory()->create(); // sem vínculo com $this->user

    Task::factory()->create([
        'taskable_type' => Team::class,
        'taskable_id' => $team->id,
    ]);

    $response = $this->getJson(route($this->endpoint, ['team' => $team->id]));

    $response->assertForbidden();
});

it('permite que admin veja tarefas de qualquer equipe', function () {
    $this->user->role = UserRole::ADMIN;
    $this->user->save();

    $team = Team::factory()->create();

    Task::factory()->count(3)->create([
        'taskable_type' => Team::class,
        'taskable_id' => $team->id,
    ]);

    $response = $this->getJson(route($this->endpoint, ['team' => $team->id]));

    $response->assertOk()->assertJsonCount(3, 'data');
});
