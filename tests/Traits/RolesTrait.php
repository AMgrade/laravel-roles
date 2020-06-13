<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Tests\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use McMatters\LaravelRoles\Models\Role;

use const null;

/**
 * Trait RolesTrait
 *
 * @package McMatters\LaravelRoles\Tests\Traits
 */
trait RolesTrait
{
    /**
     * @param string $role
     *
     * @return \McMatters\LaravelRoles\Models\Role
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
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

    /**
     * @return \McMatters\LaravelRoles\Models\Role
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getRoleAdmin(): Role
    {
        return $this->getRole('admin');
    }

    /**
     * @return \McMatters\LaravelRoles\Models\Role
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getRoleModerator(): Role
    {
        return $this->getRole('moderator');
    }

    /**
     * @return \McMatters\LaravelRoles\Models\Role
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getRoleEditor(): Role
    {
        return $this->getRole('editor');
    }
}
