<?php

use Team\Application\DTOs\TeamDTO;

uses(Tests\TestCase::class);

it('cria o DTO com os dados corretos', function () {
    $dto = new TeamDTO(
        id: 'random_id',
        name: 'João Silva',
    );

    expect($dto->name)->toBe('João Silva');
    expect($dto->id)->toBe('random_id');
});

it('cria o DTO com id nula', function () {
    $dto = new TeamDTO(
        id:null,
        name: 'João Silva',
    );

    expect($dto->name)->toBe('João Silva');
    expect($dto->id)->toBeNull();
});
