<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Tests;

use McMatters\LaravelRoles\ServiceProvider;
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

        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(realpath('migrations'));
        $this->artisan('db:seed', ['--class' => 'UserRolePermissionSeeder'])->run();

        $this->withFactories(realpath('tests/database/factories'));
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
}
