<?php

use Illuminate\Support\Facades\Validator;
use User\Interface\Http\Requests\V1\UpdateUserRequest;
use Auth\Infrastructure\Persistence\Model\User;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('permite atualizar com dados válidos', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $request = new UpdateUserRequest();
    $request->setUserResolver(fn() => $user);

    $data = [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeFalse();
});

it('rejeita email já registrado por outro usuário', function () {
    $user = User::factory()->create(['email' => 'current@example.com']);
    User::factory()->create(['email' => 'taken@example.com']);

    $request = new UpdateUserRequest();
    $request->setUserResolver(fn () => $user);

    $data = ['email' => 'taken@example.com'];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('email'))->toBe('The email has already been taken.');
});


it('rejeita nome com caracteres inválidos', function () {
    $user = User::factory()->create();

    $request = new UpdateUserRequest();
    $request->setUserResolver(fn () => $user);

    $data = ['name' => 'Jane_123'];

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('name'))->toBe('The name field format is invalid.');
});
