<?php

use Auth\Application\UseCases\RegisterCase;
use Auth\Application\DTOs\UserRegisterDTO;
use Auth\Application\Interfaces\AuthRepositoryInterface;
use Auth\Domain\Entities\User;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class);

it('registra um novo usuário com dados válidos', function () {
    $dto = new UserRegisterDTO(
        name: 'João Silva',
        role: 'member',
        email: 'joao@example.com',
        password: 'hashed-password'
    );

    $expectedUser = new User(
        null,
        name: 'João Silva',
        role: 'member',
        email: 'joao@example.com',
        password: $dto->password
    );

    $mockRepo = $this->mock(AuthRepositoryInterface::class);
    $mockRepo->shouldReceive('save')
        ->once()
        ->with(Mockery::on(function (User $user) use ($dto) {
            return $user->name === $dto->name &&
                $user->role === $dto->role &&
                $user->email === $dto->email &&
                $user->password === $dto->password;
        }))
        ->andReturn($expectedUser);

    $useCase = new RegisterCase($mockRepo);
    $user = $useCase->execute($dto);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->email)->toBe('joao@example.com');
    expect(Hash::check('hashed-password', $user->password))->toBeTrue();
});
