<?php

namespace Task\Policies;

use Auth\Infrastructure\Persistence\Model\User;
use Task\Infrastructure\Persistence\Model\Task;
use Team\Infrastructure\Persistence\Model\Team;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        if ($task->taskable_type === User::class) {
            return $user->id === $task->taskable_id;
        }

        if ($task->taskable_type === Team::class) {
            return $user->hasAnyRole('admin') || $task->taskable->users()->where('id', $user->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole('admin', 'manager', 'member');
    }

    public function update(User $user, Task $task): bool
    {
        if ($task->taskable_type === User::class) {
            return $user->id === $task->taskable_id;
        }

        if ($task->taskable_type === Team::class) {
            return $user->hasAnyRole('admin') || $task->taskable?->managers()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    public function delegate(User $user): bool
    {
        return $user->hasAnyRole('admin', 'manager');
    }


    public function delete(User $user, Task $task): bool
    {
        return $this->update($user, $task); // mesma regra
    }
}
