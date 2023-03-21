<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Tests\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use McMatters\LaravelRoles\Models\Role;

use const null;

trait RolesTrait
{
    protected function getRole(string $role): Role
    {
        static $cache;

        if (null === $cache) {
            $cache = Role::all()->keyBy('name');
        }

        return $cache->get($role, static function () {
            throw (new ModelNotFoundException())->setModel(Role::class);
        });
    }

    protected function getRoleAdmin(): Role
    {
        return $this->getRole('admin');
    }

    protected function getRoleModerator(): Role
    {
        return $this->getRole('moderator');
    }

    protected function getRoleEditor(): Role
    {
        return $this->getRole('editor');
    }
}
