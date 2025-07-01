<?php

namespace Task\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Task\Application\Interfaces\TaskRepositoryInterface;
use Task\Infrastructure\Repositories\EloquentTaskRepository;
use Task\Application\Interfaces\TaskAssignmentValidatorInterface;
use Task\Infrastructure\Observers\TaskObserver;
use Task\Infrastructure\Persistence\Model\Task;
use Task\Infrastructure\Services\TaskAssignmentValidator;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class,  EloquentTaskRepository::class);
        $this->app->bind(TaskAssignmentValidatorInterface::class, TaskAssignmentValidator::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Task::observe(TaskObserver::class);

        Gate::policy(Task::class, \Task\Policies\TaskPolicy::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Persistence/Migrations');
    }
}
