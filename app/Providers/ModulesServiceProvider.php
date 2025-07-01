<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
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
        // registrar todos os módulos dinamicamente :)
        foreach (glob(app_path('Modules/*/Providers/*ServiceProvider.php')) as $provider) {
            $class = str_replace([app_path(), '/', '.php',], ['App', '\\', ''], $provider);
            $class = rtrim(str_replace('App\\Modules\\', '', $class));

            $this->app->register($class);
        }
    }
}
