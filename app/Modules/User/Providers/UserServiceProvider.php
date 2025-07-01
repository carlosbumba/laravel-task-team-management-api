<?php

namespace User\Providers;

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use User\Policies\UserPolicy;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Persistence/Migrations');
    }
}
