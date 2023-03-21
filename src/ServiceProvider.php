<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

use function method_exists;

class ServiceProvider extends BaseServiceProvider
{
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

    protected function registerBladeDirectives(): void
    {
        /** @var \Illuminate\View\Compilers\BladeCompiler $blade */
        $blade = $this->app
            ->make('view')
            ->getEngineResolver()
            ->resolve('blade')
            ->getCompiler();

        $blade->if('role', static function ($role) {
            return Auth::check() && Auth::user()->hasRoles($role);
        });

        $blade->if('anyrole', static function ($role) {
            return Auth::check() && Auth::user()->hasAnyRole($role);
        });

        $blade->if('permission', static function ($permission) {
            return Auth::check() && Auth::user()->hasPermissions($permission);
        });

        $blade->if('anypermission', static function ($permission) {
            return Auth::check() && Auth::user()->hasAnyPermission($permission);
        });

        $blade->if('level', static function ($level) {
            return Auth::check() && Auth::user()->levelAccess() >= $level;
        });
    }

    protected function configPath(): string
    {
        return method_exists($this->app, 'configPath')
            ? $this->app->configPath()
            : "{$this->app->basePath()}/config";
    }
}
