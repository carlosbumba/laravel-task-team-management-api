<?php

namespace Auth\Providers;

use Auth\Application\Interfaces\AuthRepositoryInterface;
use Auth\Infrastructure\Repositories\EloquentAuthRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class,  EloquentAuthRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(\Auth\Infrastructure\Persistence\Model\User::class, \User\Policies\UserPolicy::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Persistence/Migrations');
    }
}
