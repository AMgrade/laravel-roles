<?php

declare(strict_types=1);

namespace AMgrade\Roles\Tests\Traits;

use AMgrade\Roles\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
