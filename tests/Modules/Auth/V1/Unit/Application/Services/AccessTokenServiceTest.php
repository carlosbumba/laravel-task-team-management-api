<?php

use Auth\Application\Interfaces\AuthRepositoryInterface;
use Auth\Application\Services\AccessTokenService;

uses(Tests\TestCase::class);

it('gera token a partir do user id', function () {
    $mockRepo = $this->mock(AuthRepositoryInterface::class);
    $mockRepo->shouldReceive('generateToken')
        ->with('1')
        ->andReturn('fake-token');

    $service = new AccessTokenService($mockRepo);

    $token = $service->generateAccessToken('1');

    expect($token)->toBe('fake-token');
});
