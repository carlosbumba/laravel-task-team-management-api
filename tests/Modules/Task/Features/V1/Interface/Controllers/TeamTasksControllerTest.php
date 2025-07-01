<?php

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Shared\Domain\Enums\UserRole;
use Task\Infrastructure\Persistence\Model\Task;
use Team\Infrastructure\Persistence\Model\Team;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->member()->create();
    $this->endpoint = 'api.v1.team.tasks';

    Sanctum::actingAs($this->user);
});

it('permite que um membro da equipe visualize as tarefas da equipe', function () {
    $team = Team::factory()
        ->hasAttached($this->user, ['role_in_team' => 'member'])
        ->create();

    Task::factory()
        ->count(3)
        ->create([
            'taskable_type' => Team::class,
            'taskable_id' => $team->id,
        ]);

    $this->getJson(route($this->endpoint, $team->id))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('impede que usuários não membros visualizem tarefas da equipe', function () {
    $team = Team::factory()->create();

    $this->getJson(route($this->endpoint, $team->id))
        ->assertForbidden();
});

it('permite que um admin visualize tarefas de qualquer equipe', function () {
    $this->user->role = UserRole::ADMIN;
    $this->user->save();

    $team = Team::factory()->create();

    $this->getJson(route($this->endpoint, $team->id))
        ->assertOk()
        ->assertJsonCount(0, 'data'); // nenhuma tarefa criada
});

it('retorna lista vazia se equipe não possui tarefas', function () {
    $team = Team::factory()->hasAttached($this->user, ['role_in_team' => 'member'])->create();

    $this->getJson(route($this->endpoint, $team->id))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
