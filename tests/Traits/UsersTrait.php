<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Tests\Traits;

use AMgrade\LaravelRoles\Tests\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use const null;

trait UsersTrait
{
    protected function createUser(): User
    {
        return User::factory()->count(1)->create()->first();
    }

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

    protected function getUserAdmin(): User
    {
        return $this->getUser('admin');
    }

    protected function getUserModerator(): User
    {
        return $this->getUser('moderator');
    }

    protected function getUserEditor(): User
    {
        return $this->getUser('editor');
    }
}
