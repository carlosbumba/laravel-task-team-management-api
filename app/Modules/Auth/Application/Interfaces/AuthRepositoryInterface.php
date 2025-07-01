<?php

namespace Auth\Application\Interfaces;

use Auth\Domain\Entities\User;

interface AuthRepositoryInterface
{
    public function save(User $user): User;

    public function findById(string $id): ?User;

    public function findByEmail(string $email): ?User;

    public function generateToken(string $id): string;
}
