<?php

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Shared\Domain\Enums\UserRole;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->route_prefix = 'api.v1.users.';

    User::truncate();

    $this->user = User::factory()->member()->create();
    $this->admin = User::factory()->admin()->create();

    Sanctum::actingAs($this->admin);
});

it('permite que o usuário autenticado visualize seus próprios dados via /me', function () {
    $response = $this->getJson(route($this->route_prefix . 'me'));

    $response->assertOk()
        ->assertJsonFragment(['id' => $this->admin->id]);
});

it('permite que um usuário com papel "admin" liste todos os usuários', function () {
    $response = $this->getJson(route($this->route_prefix . 'index'));

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

it('permite que um usuário com papel "manager" liste todos os usuários', function () {
    $this->admin->update(['role' => UserRole::MANAGER]);

    $response = $this->getJson(route($this->route_prefix . 'index'));

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

it('impede que um usuário com papel "member" liste os usuários', function () {
    Sanctum::actingAs($this->user);

    $response = $this->getJson(route($this->route_prefix . 'index'));

    $response->assertForbidden();
});

it('permite que o usuário visualize seus próprios dados via rota /show/{id}', function () {
    Sanctum::actingAs($this->user);

    $response = $this->getJson(route($this->route_prefix . 'show', $this->user->id));

    $response->assertOk()
        ->assertJsonFragment(['id' => $this->user->id]);
});

it('impede que o usuário visualize os dados de outro usuário', function () {
    Sanctum::actingAs($this->user);

    $response = $this->getJson(route($this->route_prefix . 'show', $this->admin->id));

    $response->assertForbidden();
});

it('permite que um usuário com papel "admin" visualize dados de outro usuário', function () {
    $response = $this->getJson(route($this->route_prefix . 'show', $this->user->id));

    $response->assertOk()
        ->assertJsonFragment(['id' => $this->user->id]);
});

it('permite que um usuário com papel "manager" visualize dados de outro usuário', function () {
    $this->admin->update(['role' => UserRole::MANAGER]);

    $response = $this->getJson(route($this->route_prefix . 'show', $this->user->id));

    $response->assertOk()
        ->assertJsonFragment(['id' => $this->user->id]);
});
