<?php

namespace User\Application\Services;

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Pagination\Paginator;

class UserService
{
    public function getAllOtherUsers(string $currentUserId): Paginator
    {
        return User::where('id', '<>', $currentUserId)->simplePaginate();
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
}
