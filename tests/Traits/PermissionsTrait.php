<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Tests\Traits;

use AMgrade\LaravelRoles\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function func_get_args;
use function is_array;

use const null;

trait PermissionsTrait
{
    protected function getPermission(string $name): Permission
    {
        static $cache ;

        if (null === $cache) {
            $cache = Permission::all()->keyBy('name');
        }

        return $cache->get($name, static function () {
            throw (new ModelNotFoundException())->setModel(Permission::class);
        });
    }

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
