<?php

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Team\Domain\Entities\Team as TeamEntity;
use Team\Application\UseCases\CreateTeamCase;
use Team\Application\UseCases\DeleteTeamUseCase;
use Team\Application\UseCases\GetAllTeamsUseCase;
use Team\Application\UseCases\GetTeamByIdUseCase;
use Team\Application\UseCases\UpdateTeamUseCase;

use Team\Infrastructure\Persistence\Model\Team as EloquentTeam;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->manager()->create();

    Sanctum::actingAs($this->user);
});

it('retorna a lista de equipes com status 200', function () {
    $mock = $this->mock(GetAllTeamsUseCase::class);
    $mock->shouldReceive('execute')
        ->once()
        ->andReturn([
            new TeamEntity('01HXYZ', 'Equipe 1'),
            new TeamEntity('01HXY2', 'Equipe 2'),
        ]);

    app()->instance(GetAllTeamsUseCase::class, $mock);

    $response = $this->getJson(route('api.v1.teams.index'));

    $response->assertOk()
        ->assertJsonStructure(['data' => [['id', 'name']]]);
});

it('cria uma equipe e retorna 201', function () {
    $mock = $this->mock(CreateTeamCase::class);
    $mock->shouldReceive('execute')->once()->andReturn(new TeamEntity('01HTASK', 'Nova Equipe'));

    app()->instance(CreateTeamCase::class, $mock);

    $response = $this->postJson(route('api.v1.teams.store'), [
        'name' => 'Nova Equipe'
    ]);

    $response->assertCreated()->assertJsonPath('data.name', 'Nova Equipe');
});

it('exibe uma equipe específica com sucesso', function () {
    $team = EloquentTeam::factory()->create();

    $mock = $this->mock(GetTeamByIdUseCase::class);
    $mock->shouldReceive('execute')->never(); // não é chamado

    app()->instance(GetTeamByIdUseCase::class, $mock);

    $response = $this->getJson(route('api.v1.teams.show', $team->id));

    $response->assertOk()
        ->assertJsonStructure(['data' => ['id', 'name']]);
});

it('atualiza uma equipe e retorna status 200', function () {
    $mock = $this->mock(UpdateTeamUseCase::class);
    $mock->shouldReceive('execute')->once()->andReturn(new TeamEntity('01HXYZ', 'Equipe Atualizada'));

    app()->instance(UpdateTeamUseCase::class, $mock);

    $response = $this->putJson(route('api.v1.teams.update', '01HXYZ'), [
        'name' => 'Equipe Atualizada'
    ]);

    $response->assertOk()->assertJsonPath('data.name', 'Equipe Atualizada');
});

it('deleta uma equipe (via admin) e retorna status 204', function () {
    $mock = $this->mock(DeleteTeamUseCase::class);
    $mock->shouldReceive('execute')->once()->with('01HXYZ');

    app()->instance(DeleteTeamUseCase::class, $mock);

    $response = $this->actingAs($this->admin)->deleteJson(route('api.v1.teams.destroy', '01HXYZ'));

    $response->assertNoContent();
});

it('impede manager de deletar uma equipe', function () {
    $mock = $this->mock(DeleteTeamUseCase::class);
    $mock->shouldReceive('execute')->never()->with('01HXYZ');

    app()->instance(DeleteTeamUseCase::class, $mock);

    $response = $this->deleteJson(route('api.v1.teams.destroy', '01HXYZ'));

    $response->assertStatus(403);
});
