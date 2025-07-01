<?php

namespace Auth\Application\Services;

use Auth\Application\Interfaces\AuthRepositoryInterface;

class AccessTokenService
{
    public function __construct(
        private AuthRepositoryInterface $repository
    ) {}

    public function generateAccessToken(string $id): string
    {
        return $this->repository->generateToken($id);
    }
}
