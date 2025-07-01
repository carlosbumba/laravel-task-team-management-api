<?php

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Team\Application\UseCases\GetTeamMembersUseCase;
use Laravel\Sanctum\Sanctum;
use Shared\Domain\Enums\UserRole;
use Team\Application\Mappers\TeamMemberMapper;
use Team\Application\UseCases\AddTeamMemberUseCase;
use Team\Application\UseCases\RemoveTeamMemberUseCase;
use Team\Domain\Enums\MemberRole;
use Team\Exceptions\DuplicateTeamMemberException;
use Team\Exceptions\InvalidTeamMemberException;
use Team\Infrastructure\Persistence\Model\Team;
use Team\Infrastructure\Persistence\Model\TeamMember;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->route_prefix = 'api.v1.members.';

    $this->user = User::factory()->manager()->create();

    Sanctum::actingAs($this->user);
});

it('permite listar membros de uma equipe', function () {
    $team = Team::factory()->has(TeamMember::factory()->count(4), 'members')->create();
    $members = $team->members;

    $mock = $this->mock(GetTeamMembersUseCase::class);
    $mock->shouldReceive('execute')->with($team->id)->once()->andReturn($members);

    $this->app->instance(GetTeamMembersUseCase::class, $mock);

    $response = $this->getJson(route($this->route_prefix . 'show', $team));

    $response->assertOk();
    $response->assertJsonCount(4, 'data');
});

it('impede que um usuário "member" liste membros de uma equipe', function () {
    $this->user->role = UserRole::MEMBER;
    $this->user->save();

    $team = Team::factory()->create();

    $response = $this->getJson(route($this->route_prefix . 'show', $team));

    $response->assertForbidden();
});

it('permite adicionar novo membro a equipe', function () {
    $team = Team::factory()->has(TeamMember::factory(), 'members')->create();
    $member = TeamMemberMapper::fromModel($team->members()->first());

    $mock = $this->mock(AddTeamMemberUseCase::class);
    $mock->shouldReceive('execute')->once()->andReturn($member);

    $this->app->instance(AddTeamMemberUseCase::class, $mock);

    $response = $this->postJson(route($this->route_prefix . 'add', $team), [
        'user_id' => User::factory()->create()->id,
        'role_in_team' => MemberRole::MEMBER
    ]);

    $response->assertOk();
});

it('impede adicionar membro que ja existe na equipe', function () {
    $team = Team::factory()->create();
    $member = User::factory()->create();

    $team->members()->create(['user_id' => $member->id, 'role_in_team' => MemberRole::MEMBER]);

    $mock = $this->mock(AddTeamMemberUseCase::class);
    $mock->shouldReceive('execute')->once()->andThrow(DuplicateTeamMemberException::class);

    $this->app->instance(AddTeamMemberUseCase::class, $mock);

    $response = $this->postJson(route($this->route_prefix . 'add', $team), [
        'user_id' => $member->id,
        'role_in_team' => MemberRole::MEMBER
    ]);

    $response->assertStatus(422);
});

it('permite excluir membro da equipe', function () {
    $team = Team::factory()->create();
    $member = User::factory()->create();

    $team->members()->create(['user_id' => $member->id, 'role_in_team' => MemberRole::MEMBER]);

    $mock = $this->mock(RemoveTeamMemberUseCase::class);
    $mock->shouldReceive('execute')->with($team->id, $member->id)->once();

    $this->app->instance(RemoveTeamMemberUseCase::class, $mock);

    $response = $this->deleteJson(route($this->route_prefix . 'remove', [$team->id, $member->id]));

    $response->assertNoContent();
});


it('retorna erro ao excluir membro inválido', function () {
    $team = Team::factory()->create();
    $member = User::factory()->create();

    $mock = $this->mock(RemoveTeamMemberUseCase::class);
    $mock->shouldReceive('execute')->once()->with($team->id, $member->id)->andThrow(InvalidTeamMemberException::class);

    $this->app->instance(RemoveTeamMemberUseCase::class, $mock);

    $response = $this->deleteJson(route($this->route_prefix . 'remove', [$team->id, $member->id]));

    $response->assertStatus(422);
});


test('permite que usuários "member" retornem equipes que faz parte', function () {
    $this->user->role = UserRole::MEMBER;
    $this->user->save();

    Team::factory()->hasAttached($this->user, ['role_in_team' => 'member'])->count(3)->create();

    $response = $this->getJson(route($this->route_prefix . 'my-teams'));

    $response->assertOk();
    $response->assertJsonPath('total', 3);
});
