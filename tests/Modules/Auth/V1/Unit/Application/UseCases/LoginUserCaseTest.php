<?php

use Auth\Application\UseCases\LoginCase;
use Auth\Application\DTOs\UserLoginDTO;
use Auth\Application\Interfaces\AuthRepositoryInterface;
use Auth\Domain\Entities\User;
use Auth\Exceptions\InvalidPasswordException;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class);

it('retorna token quando credenciais são válidas', function () {
    $dto = new UserLoginDTO('user@example.com', 'ValidPassword');

    $user = new User(
        id: 1,
        name: 'João Silva',
        role: 'member',
        email: 'user@example.com',
        password: bcrypt('ValidPassword'),
    );

    $mock = $this->mock(AuthRepositoryInterface::class);
    $mock->shouldReceive('findByEmail')
        ->with($dto->email)
        ->andReturn($user);

    $mock->shouldReceive('generateToken')
        ->with(1)
        ->andReturn('mocked-token');

    $useCase = new LoginCase($mock);
    $token = $useCase->execute($dto);

    expect($token)->toBe('mocked-token');
});

it('lança exceção se a senha estiver incorreta', function () {
    $dto = new UserLoginDTO('user@example.com', 'WrongPassword');

    $user = new User(
        id: 1,
        name: 'João Silva',
        role: 'member',
        email: 'user@example.com',
        password: Hash::make('CorrectPassword'),
    );

    $mock = $this->mock(AuthRepositoryInterface::class);
    $mock->shouldReceive('findByEmail')->with($dto->email)->andReturn($user);

    $useCase = new LoginCase($mock);

    expect(fn() => $useCase->execute($dto))->toThrow(InvalidPasswordException::class, 'The password is incorrect');
});
