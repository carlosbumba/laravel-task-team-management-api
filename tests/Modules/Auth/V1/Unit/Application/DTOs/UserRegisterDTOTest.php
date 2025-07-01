<?php

use Auth\Application\DTOs\UserRegisterDTO;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class);

it('cria o DTO com os dados corretos', function () {
    $dto = new UserRegisterDTO(
        name: 'João Silva',
        role: 'member',
        email: 'joao@example.com',
        password: 'SenhaSegura123'
    );

    expect($dto->name)->toBe('João Silva');
    expect($dto->role)->toBe('member');
    expect($dto->email)->toBe('joao@example.com');
});

it('hasheia a senha automaticamente', function () {
    $senhaOriginal = 'SenhaSegura123';
    $dto = new UserRegisterDTO(
        name: 'João Silva',
        role: 'member',
        email: 'joao@example.com',
        password: $senhaOriginal
    );

    expect(Hash::check($senhaOriginal, $dto->password))->toBeTrue();
});
