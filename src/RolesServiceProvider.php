<?php

declare(strict_types=1);

namespace AMgrade\Roles;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/roles.php' => "{$this->app->configPath()}/roles.php",
            ], 'config');

            $this->publishes([
                __DIR__.'/../migrations' => "{$this->app->databasePath()}/migrations",
            ], 'migrations');
        }

        $this->mergeConfigFrom(__DIR__.'/../config/roles.php', 'roles');

        $this->registerBladeDirectives();
    }

    protected function registerBladeDirectives(): void
    {
        Blade::if('role', static function ($role, ?string $guard = null) {
            return (bool) Auth::guard($guard)?->user()?->hasRoles($role);
        });

        Blade::if('anyRole', static function ($role, ?string $guard = null) {
            return (bool) Auth::guard($guard)?->user()?->hasAnyRole($role);
        });

        Blade::if('permission', static function ($permission, ?string $guard = null) {
            return (bool) Auth::guard($guard)?->user()?->hasPermissions($permission);
        });

        Blade::if('anyPermission', static function ($permission, ?string $guard = null) {
            return (bool) Auth::guard($guard)?->user()?->hasAnyPermission($permission);
        });

        Blade::if('level', static function ($level, ?string $guard = null) {
            return (bool) Auth::guard($guard)?->user()?->levelAccess() >= $level;
        });
    }
}
