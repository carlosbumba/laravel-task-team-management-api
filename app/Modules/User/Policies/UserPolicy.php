<?php

namespace User\Policies;

use Auth\Infrastructure\Persistence\Model\User;
use Shared\Domain\Enums\UserRole;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(UserRole::ADMIN->value, UserRole::MANAGER->value);
    }

    public function view(User $authUser, User $targetUser): bool
    {
        return $this->viewAny($authUser) || $authUser->id === $targetUser->id;
    }

    public function update(User $authUser, User $targetUser): bool
    {
        return $this->view($authUser, $targetUser);
    }
}
