<?php

namespace Team\Policies;

use Shared\Domain\Enums\UserRole;
use Auth\Infrastructure\Persistence\Model\User;
use Team\Infrastructure\Persistence\Model\Team;

class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole('admin', 'manager');
    }

    public function view(User $user, Team $team): bool
    {

        return match ($user->role) {
            UserRole::MEMBER => $this->isMemberOfTeam($user, $team),
            UserRole::ADMIN, UserRole::MANAGER => true
        };
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole('admin', 'manager');
    }

    public function update(User $user): bool
    {
        return $user->hasAnyRole('admin', 'manager');
    }

    public function delete(User $user): bool
    {
        return $user->role->is(UserRole::ADMIN);
    }

    public function addMember(User $user): bool
    {
        return $user->hasAnyRole('admin', 'manager');
    }

    public function removeMember(User $user): bool
    {
        return $user->role->is(UserRole::ADMIN);
    }

    protected function isMemberOfTeam(User $user, Team $team): bool
    {
        return $team->members()
            ->where('user_id', $user->id)
            ->exists();
    }
}
