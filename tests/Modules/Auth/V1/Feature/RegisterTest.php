<?php

use Shared\Domain\Enums\UserRole;
use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

function registerRoute(): string
{
    return route('api.v1.register');
}

function validPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Eugénio Calesse',
        'email' => 'eugeniocalesse@gmail.com',
        'password' => 'CCC-C20-20a',
        'role' => fake()->randomElement(UserRole::values())
    ], $overrides);
}

it('permite registro com dados válidos', function () {
    $response = $this->postJson(registerRoute(), validPayload());

    $response->assertCreated();
});

it('retorna access_token após registro', function () {
    $response = $this->postJson(registerRoute(), validPayload());

    $response->assertCreated()->assertJsonStructure(['access_token']);
});

it('rejeita registro com campos obrigatórios ausentes', function () {
    $response = $this->postJson(registerRoute(), validPayload([
        'email' => '',
        'password' => ''
    ]));

    $response->assertJsonValidationErrors(['email', 'password']);
});

it('rejeita nome inválido no registro', function () {
    $response = $this->postJson(registerRoute(), validPayload([
        'name' => 'calesse_123'
    ]));

    $response->assertJsonValidationErrors(['name']);
});

it('rejeita email com formato inválido', function () {
    $response = $this->postJson(registerRoute(), validPayload([
        'email' => 'calesse_@12@gmail.com'
    ]));

    $response->assertJsonValidationErrors(['email']);
});

it('rejeita senha fraca no registro', function () {
    $response = $this->postJson(registerRoute(), validPayload([
        'password' => 'calesse123'
    ]));

    $response->assertJsonValidationErrors(['password']);
});

it('rejeita nome duplicado no registro', function () {
    $name = 'Eugénio Calesse';
    User::factory()->create(['name' => $name]);

    $response = $this->postJson(registerRoute(), validPayload([
        'name' => $name
    ]));

    $response->assertJsonValidationErrors(['name']);
});

it('rejeita email duplicado no registro', function () {
    $email = 'calesse01@gmai.com';
    User::factory()->create(['email' => $email]);

    $response = $this->postJson(registerRoute(), validPayload([
        'email' => $email
    ]));

    $response->assertJsonValidationErrors(['email']);
});

it('rejeita role ausente no registro', function () {
    $response = $this->postJson(registerRoute(), validPayload([
        'role' => ''
    ]));

    $response->assertJsonValidationErrors(['role']);
});

it('rejeita role inválido no registro', function () {
    $response = $this->postJson(registerRoute(), validPayload([
        'role' => 'director'
    ]));

    $response->assertJsonValidationErrors(['role']);
});
