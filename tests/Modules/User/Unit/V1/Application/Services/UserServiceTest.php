<?php

use User\Application\Services\UserService;
use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    User::truncate();
});


it('retorna todos os usuários exceto o usuário atual', function () {
    $currentUser = User::factory()->create();
    $others = User::factory()->count(5)->create();

    $service = new UserService();
    $result = $service->getAllOtherUsers($currentUser->id);

    expect($result)->toHaveCount(5)
        ->and($result->pluck('id'))->not->toContain($currentUser->id);
});


it('atualiza os dados do usuário', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    $service = new UserService();

    $updatedUser = $service->update($user, [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);

    expect($updatedUser->fresh()->name)->toBe('Updated Name')
        ->and($updatedUser->email)->toBe('updated@example.com');
});

it('mantém os dados ao atualizar com array vazio', function () {
    $user = User::factory()->create([
        'name' => 'Ana',
        'email' => 'ana@example.com',
    ]);

    $service = new UserService();
    $updated = $service->update($user, []);

    expect($updated->fresh()->name)->toBe('Ana')
        ->and($updated->email)->toBe('ana@example.com');
});
