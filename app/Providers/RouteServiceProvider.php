<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $version = 1;
        $maxVersions = 3;

        do {
            $paths = $this->getModuleRoutes("api_v{$version}.php");

            if ($paths->isNotEmpty()) {
                $this->registerApiVersion($version, $paths);
                $version++;
            }
        } while ($paths->isNotEmpty() && $version <= $maxVersions);
    }

    protected function getModuleRoutes(string $filename): Collection
    {
        return collect(glob(base_path("app/Modules/*/Interface/Routes/{$filename}")));
    }

    protected function registerApiVersion(int $version, Collection $paths): void
    {
        Route::prefix("api/v{$version}")
            ->name("api.v{$version}.")
            ->middleware(['api', 'throttle:60,1'])
            ->group(function () use ($paths) {
                foreach ($paths as $routeFile) {
                    require $routeFile;
                }
            });
    }
}
