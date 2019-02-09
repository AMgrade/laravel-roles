<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use function method_exists;

/**
 * Class ServiceProvider
 *
 * @package McMatters\LaravelRoles
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/roles.php' => $this->configPath().'/roles.php',
            ], 'config');

            $this->publishes([
                __DIR__.'/../migrations' => $this->app->databasePath().'/migrations',
            ], 'migrations');
        }

        $this->mergeConfigFrom(__DIR__.'/../config/roles.php', 'roles');

        $this->registerBladeDirectives();
    }

    /**
     * @return void
     */
    protected function registerBladeDirectives(): void
    {
        Blade::if('role', function ($role) {
            return Auth::check() && Auth::user()->hasRoles($role);
        });

        Blade::if('permission', function ($permission) {
            return Auth::check() && Auth::user()->hasPermissions($permission);
        });

        Blade::if('level', function ($level) {
            return Auth::check() && Auth::user()->levelAccess() >= $level;
        });
    }

    /**
     * @return string
     */
    protected function configPath(): string
    {
        return method_exists($this->app, 'configPath')
            ? $this->app->configPath()
            : $this->app->basePath().'/config';
    }
}
