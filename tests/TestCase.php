<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use McMatters\LaravelRoles\ServiceProvider;
use McMatters\LaravelRoles\Tests\Database\Seeders\UserRolePermissionSeeder;
use McMatters\LaravelRoles\Tests\Models\User;
use McMatters\LaravelRoles\Tests\Traits\RolesTrait;
use McMatters\LaravelRoles\Tests\Traits\UsersTrait;
use Orchestra\Testbench\TestCase as BaseTestCase;

use function realpath;

/**
 * Class TestCase
 *
 * @package McMatters\LaravelRoles\Tests
 */
class TestCase extends BaseTestCase
{
    use RolesTrait;
    use UsersTrait;

    /**
     * @return void
     *
     * @throws \Mockery\Exception\NoMatchingExpectationException
     */
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
     *
     * @return void
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
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    /**
     * @return array
     */
    protected function getAnnotations(): array
    {
        return [];
    }

    /**
     * @return void
     */
    protected function setupEloquentFactory(): void
    {
        $namespace = 'McMatters\\LaravelRoles\\Tests\\Database\\Factories\\';

        Factory::useNamespace($namespace);

        Factory::guessFactoryNamesUsing(static function (string $modelName) use ($namespace) {
            $modelName = Str::startsWith($modelName, __NAMESPACE__.'\\Models\\')
                ? Str::after($modelName, __NAMESPACE__.'\\Models\\')
                : Str::after($modelName, __NAMESPACE__);

            return "{$namespace}{$modelName}Factory";
        });
    }
}
