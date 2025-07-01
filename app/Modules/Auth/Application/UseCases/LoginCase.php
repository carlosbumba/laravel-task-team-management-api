<?php

namespace Auth\Application\UseCases;

use Auth\Application\DTOs\UserLoginDTO;
use Auth\Application\Interfaces\AuthRepositoryInterface;
use Auth\Exceptions\InvalidPasswordException;
use Illuminate\Support\Facades\Hash;

class LoginCase
{
    public function __construct(private AuthRepositoryInterface $repository) {}

    public function execute(UserLoginDTO $dto): string
    {
        // Email already validated and confirmed via FormRequest
        $user = $this->repository->findByEmail($dto->email);

        if (!Hash::check($dto->password, $user->password)) {
            throw new InvalidPasswordException('The password is incorrect');
        }

        return $this->repository->generateToken($user->id);
    }
}
