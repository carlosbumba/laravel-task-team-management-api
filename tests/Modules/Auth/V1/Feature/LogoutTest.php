<?php

uses(Tests\TestCase::class);

it('revoga token atual no logout', function () {
    $user = \Auth\Infrastructure\Persistence\Model\User::factory()->create();
    $token = $user->createToken('access_token')->plainTextToken;

    $this->withToken($token)
         ->getJson(route('api.v1.logout'))
         ->assertOk()
         ->assertJson(['success' => 'Logout realizado']);
});
