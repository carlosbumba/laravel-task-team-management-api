<?php

namespace Auth\Application\UseCases;

use Auth\Application\DTOs\UserRegisterDTO;
use Auth\Application\Interfaces\AuthRepositoryInterface;
use Auth\Domain\Entities\User;

class RegisterCase
{
    public function __construct(private AuthRepositoryInterface $repository) {}

    public function execute(UserRegisterDTO $dto): User
    {
        return $this->repository->save(new User(null, $dto->name, $dto->role, $dto->email, $dto->password));
    }
}
