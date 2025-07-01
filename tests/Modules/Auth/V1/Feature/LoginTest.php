<?php

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('permite login com credenciais válidas', function () {
    $password = 'Password_155';
    $user = User::factory()->create(['password' => Hash::make($password)]);

    $response = $this->postJson(route('api.v1.login'), [
        'email' => $user->email,
        'password' => $password
    ]);

    $response->assertStatus(200);
});

it('retorna access_token no login bem-sucedido', function () {
    $password = 'Password_155';
    $user = User::factory()->create(['password' => Hash::make($password)]);

    $response = $this->postJson(route('api.v1.login'), [
        'email' => $user->email,
        'password' => $password
    ]);

    $response->assertStatus(200)->assertJsonStructure(['access_token']);
});

it('não permite login com email não registrado', function () {
    $response = $this->postJson(route('api.v1.login'), [
        'email' => 'unregisted@gmail.com',
        'password' => 'Password_123'
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors(['email' => 'No user found with this email address.']);
});

it('não permite login com senha inválida', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('api.v1.login'), [
        'email' => $user->email,
        'password' => 'Password_000'
    ]);

    $response->assertStatus(422)->assertJsonFragment(['error' => 'The password is incorrect']);
});

it('exige campo de email', function () {
    $response = $this->postJson(route('api.v1.login'), [
        'password' => 'Password_000'
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors(['email' => 'The email field is required.']);
});

it('exige campo de senha', function () {
    $response = $this->postJson(route('api.v1.login'), [
        'email' => 'eugenio202@gmail.com'
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors(['password' => 'The password field is required.']);
});

it('valida campo de email como formato válido', function () {
    $response = $this->postJson(route('api.v1.login'), [
        'email' => 'eugeni#o202@gmail',
        'password' => 'Password_123'
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors(['email' => 'Please provide a valid email address.']);
});
