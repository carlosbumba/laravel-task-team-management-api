<?php

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Task\Infrastructure\Services\TaskAssignmentValidator;
use Team\Infrastructure\Persistence\Model\Team;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->team = Team::factory()->create();

    $this->admin = User::factory()->admin()->create();
    $this->member = User::factory()->member()->create();
    $this->manager = User::factory()->manager()->create();

    $this->validator = new TaskAssignmentValidator;
});

//
// Testes para canAssignToUser()
//

it('permite que um usuário "member" atribua tarefas apenas a si mesmo', function () {
    expect($this->validator->canAssignToUser($this->member->id, $this->member->id))->toBeTrue();
    expect($this->validator->canAssignToUser($this->member->id, 'another-user-id'))->toBeFalse();
});

it('impede que um usuário "manager" atribua tarefas a outros usuários', function () {
    expect($this->validator->canAssignToUser($this->manager->id, 'another-user-id'))->toBeFalse();
});

it('permite que um usuário "admin" atribua tarefas a outros usuários', function () {
    expect($this->validator->canAssignToUser($this->admin->id, 'another-user-id'))->toBeTrue();
});

//
// Testes para canAssignToTeam()
//

it('impede que um usuário "member" atribua tarefas para qualquer equipe', function () {
    expect($this->validator->canAssignToTeam($this->member->id, $this->team->id))->toBeFalse();
});

it('impede que um "manager" atribua tarefas para equipes que não gerencia', function () {
    expect($this->validator->canAssignToTeam($this->manager->id, $this->team->id))->toBeFalse();
});

it('permite que um "manager" atribua tarefas apenas para equipes que gerencia', function () {
    $team = Team::factory()->hasAttached($this->manager, ['role_in_team' => 'manager'])->create();

    expect($this->validator->canAssignToTeam($this->manager->id, $team->id))->toBeTrue();
});

it('permite que um "admin" atribua tarefas para qualquer equipe', function () {
    expect($this->validator->canAssignToTeam($this->admin->id, $this->team->id))->toBeTrue();
});
