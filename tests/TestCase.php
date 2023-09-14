<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Tests;

use AMgrade\LaravelRoles\LaravelRolesServiceProvider;
use AMgrade\LaravelRoles\Tests\Database\Seeders\UserRolePermissionSeeder;
use AMgrade\LaravelRoles\Tests\Models\User;
use AMgrade\LaravelRoles\Tests\Traits\RolesTrait;
use AMgrade\LaravelRoles\Tests\Traits\UsersTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as BaseTestCase;

use function realpath;

class TestCase extends BaseTestCase
{
    use RolesTrait;
    use UsersTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupEloquentFactory();
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(realpath('migrations'));
        $this->artisan('db:seed', ['--class' => UserRolePermissionSeeder::class])->run();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $config = $app->make('config');

        $config->set('database.default', 'testing');
        $config->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $config->set('roles.models.user', User::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [LaravelRolesServiceProvider::class];
    }

    /**
     * @return array
     */
    protected function getAnnotations(): array
    {
        return [];
    }

    protected function setupEloquentFactory(): void
    {
        $namespace = 'AMgrade\\LaravelRoles\\Tests\\Database\\Factories\\';

        Factory::useNamespace($namespace);

        Factory::guessFactoryNamesUsing(static function (string $modelName) use ($namespace) {
            $modelName = Str::startsWith($modelName, __NAMESPACE__.'\\Models\\')
                ? Str::after($modelName, __NAMESPACE__.'\\Models\\')
                : Str::after($modelName, __NAMESPACE__);

            return "{$namespace}{$modelName}Factory";
        });
    }
}
