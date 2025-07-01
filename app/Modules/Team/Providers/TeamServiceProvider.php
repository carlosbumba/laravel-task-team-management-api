<?php

namespace Team\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Team\Application\Interfaces\TeamMemberRepositoryInterface;
use Team\Application\Interfaces\TeamRepositoryInterface;
use Team\Infrastructure\Observers\TeamObserver;
use Team\Infrastructure\Persistence\Model\Team;
use Team\Infrastructure\Repositories\EloquentTeamMemberRepository;
use Team\Infrastructure\Repositories\EloquentTeamRepository;

class TeamServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TeamRepositoryInterface::class,  EloquentTeamRepository::class);
        $this->app->bind(TeamMemberRepositoryInterface::class,  EloquentTeamMemberRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Team::observe(TeamObserver::class);

        Gate::policy(Team::class, \Team\Policies\TeamPolicy::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Persistence/Migrations');
    }
}
