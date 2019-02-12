<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Tests\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use McMatters\LaravelRoles\Models\Permission;
use const null;
use function func_get_args, is_array;

/**
 * Trait PermissionsTrait
 *
 * @package McMatters\LaravelRoles\Tests\Traits
 */
trait PermissionsTrait
{
    /**
     * @param array|string $name
     *
     * @return \McMatters\LaravelRoles\Models\Permission
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getPermission(string $name): Permission
    {
        static $cache ;

        if (null === $cache) {
            $cache = Permission::all()->keyBy('name');
        }

        return $cache->get($name, function () {
            throw new ModelNotFoundException();
        });
    }

    /**
     * @param array|string $names
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getPermissions($names): Collection
    {
        $collection = new Collection();

        $names = is_array($names) ? $names : func_get_args();

        foreach ($names as $name) {
            $collection->push($this->getPermission($name));
        }

        return $collection;
    }
}
