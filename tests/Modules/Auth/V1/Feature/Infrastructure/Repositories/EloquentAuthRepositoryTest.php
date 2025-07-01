<?php

use Auth\Domain\Entities\User as UserEntity;
use Auth\Infrastructure\Repositories\EloquentAuthRepository;
use Auth\Infrastructure\Persistence\Model\User as EloquentUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('salva um User entity corretamente no banco', function () {
    $repository = new EloquentAuthRepository();

    $entity = new UserEntity(
        id: null,
        name: 'João Silva',
        role: 'member',
        email: 'joao@example.com',
        password: Hash::make('senha123')
    );

    $saved = $repository->save($entity);

    expect($saved->id)->not->toBeNull();
    expect($saved->email)->toBe($entity->email);

    $this->assertDatabaseHas('users', [
        'id' => $saved->id,
        'email' => $saved->email,
    ]);
});

it('retorna User entity via findByEmail', function () {
    $model = EloquentUser::factory()->create([
        'email' => 'joao@example.com',
    ]);

    $repository = new EloquentAuthRepository();
    $entity = $repository->findByEmail($model->email);

    expect($entity)->toBeInstanceOf(UserEntity::class);
    expect($entity->email)->toBe($model->email);
});

it('gera token ao chamar generateToken', function () {
    $model = EloquentUser::factory()->create();

    $repository = new EloquentAuthRepository();
    $token = $repository->generateToken($model->id);

    expect($token)->toBeString();
    expect(strlen($token))->toBeGreaterThan(40);
});
