<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Tests\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use McMatters\LaravelRoles\Tests\Models\User;

use function factory;

use const null;

/**
 * Trait UsersTrait
 *
 * @package McMatters\LaravelRoles\Tests\Traits
 */
trait UsersTrait
{
    /**
     * @return \McMatters\LaravelRoles\Tests\Models\User
     */
    protected function createUser(): User
    {
        return factory(User::class, 1)->create()->first();
    }

    /**
     * @param string $name
     *
     * @return \McMatters\LaravelRoles\Tests\Models\User
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getUser(string $name): User
    {
        static $cache;

        if (null === $cache) {
            $cache = User::all()->keyBy('name');
        }

        return $cache->get($name, static function () {
            throw (new ModelNotFoundException())->setModel(User::class);
        });
    }

    /**
     * @return \McMatters\LaravelRoles\Tests\Models\User
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getUserAdmin(): User
    {
        return $this->getUser('admin');
    }

    /**
     * @return \McMatters\LaravelRoles\Tests\Models\User
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getUserModerator(): User
    {
        return $this->getUser('moderator');
    }

    /**
     * @return \McMatters\LaravelRoles\Tests\Models\User
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getUserEditor(): User
    {
        return $this->getUser('editor');
    }
}
