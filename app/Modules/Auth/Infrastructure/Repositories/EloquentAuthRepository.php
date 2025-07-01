<?php

namespace Auth\Infrastructure\Repositories;

use Auth\Application\Interfaces\AuthRepositoryInterface;
use Auth\Domain\Entities\User as UserEntity;
use Auth\Infrastructure\Persistence\Model\User as EloquentUser;

class EloquentAuthRepository implements AuthRepositoryInterface
{
    protected function toUserEntity(EloquentUser $eloquentUser): UserEntity
    {
        return new UserEntity(
            $eloquentUser->id,
            $eloquentUser->name,
            $eloquentUser->role,
            $eloquentUser->email,
            $eloquentUser->password,
            $eloquentUser->created_at,
            $eloquentUser->updated_at,
        );
    }

    public function save(UserEntity $user): UserEntity
    {
        $model = EloquentUser::create([
            'name' => $user->name,
            'role' => $user->role,
            'email' => $user->email,
            'password' => $user->password,
        ]);

        return $this->toUserEntity($model);
    }

    public function findById(string $id): UserEntity
    {
        return $this->toUserEntity(
            EloquentUser::findOrFail($id)
        );
    }

    public function findByEmail(string $email): UserEntity
    {
        return $this->toUserEntity(
            EloquentUser::where('email', $email)->firstOrFail()
        );
    }

    public function generateToken(string $id): string
    {
        $user = EloquentUser::findOrFail($id);
        return $user->createToken('auth_token')->plainTextToken;
    }
}
