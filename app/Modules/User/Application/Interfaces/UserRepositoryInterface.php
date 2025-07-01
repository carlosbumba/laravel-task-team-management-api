<?php

namespace User\Application\Interfaces;

use Auth\Domain\Entities\User as UserEntity;
use Illuminate\Contracts\Pagination\Paginator;

interface UserRepositoryInterface
{
    public function findById(string $id): UserEntity;

    public function findAllExcept(string $id): Paginator;

    public function update(UserEntity $userEntity): UserEntity;

    public function delete(string $id): void;
}
