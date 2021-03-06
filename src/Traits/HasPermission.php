<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Traits;

use Countable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use McMatters\LaravelRoles\Models\Permission;
use McMatters\LaravelRoles\Models\Role;
use const false, null, true;
use function class_uses, count, in_array, is_array, is_int, is_numeric, is_string;

/**
 * Trait HasPermission
 *
 * @package McMatters\LaravelRoles\Traits
 */
trait HasPermission
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $permissions;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            null,
            null,
            'permission_id',
            null,
            null,
            'permissions'
        );
    }

    /**
     * @param mixed $permission
     * @param bool $touch
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function attachPermission($permission, bool $touch = true): void
    {
        $this->permissions()->attach(
            (new EloquentCollection())->merge(
                $this->parsePermissions($permission, true)
            ),
            [],
            $touch
        );

        $this->flushPermissions();
    }

    /**
     * @param mixed $permission
     * @param bool $touch
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function detachPermission($permission = null, bool $touch = true): void
    {
        if (null !== $permission) {
            $permission = (new EloquentCollection())->merge(
                $this->parsePermissions($permission, true)
            );
        }

        $this->permissions()->detach($permission, $touch);

        $this->flushPermissions();
    }

    /**
     * @param mixed $permissions
     * @param bool $detaching
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function syncPermissions($permissions, bool $detaching = true): void
    {
        $this->permissions()->sync(
            $this->parsePermissions($permissions),
            $detaching
        );

        $this->flushPermissions();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPermissions(): EloquentCollection
    {
        if (null === $this->permissions) {
            /** @var EloquentCollection $permissions */
            $permissions = $this->getAttribute('permissions');

            if (in_array(HasRole::class, class_uses($this), true)) {
                $permissions = $permissions->merge($this->getRolePermissions());
            }

            $this->permissions = $permissions;
        }

        return $this->permissions;
    }

    /**
     * @return void
     */
    public function flushPermissions(): void
    {
        $this->unsetRelation('permissions');
        $this->permissions = null;
    }

    /**
     * @param mixed $permission
     *
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        return $this->getPermissions()->contains(
            function (Permission $model) use ($permission) {
                if (is_int($permission) || is_numeric($permission)) {
                    return (int) $permission === $model->getKey();
                }

                if (is_string($permission)) {
                    return $model->getAttribute('name') === $permission;
                }

                return $permission instanceof Permission
                    ? $model->is($permission)
                    : false;
            }
        );
    }

    /**
     * @param mixed $permissions
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function hasPermissions($permissions): bool
    {
        if (!$permissions = $this->parsePermissions($permissions)) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $permissions
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function hasAnyPermission($permissions): bool
    {
        if (!$permissions = $this->parsePermissions($permissions)) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $permissions
     * @param bool $load
     *
     * @return array
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function parsePermissions($permissions, bool $load = false): array
    {
        if ($permissions instanceof Collection) {
            return $permissions->map(function ($permission) {
                if (is_int($permission)) {
                    return Permission::query()->findOrFail($permission);
                }

                if (is_string($permission)) {
                    return Permission::query()
                        ->where('name', $permission)
                        ->firstOrFail();
                }

                return $permission;
            })->all();
        }

        if (is_string($permissions)) {
            $permissions = explode('|', $permissions);
        }

        if (!$load || $permissions instanceof Permission) {
            return Arr::wrap($permissions);
        }

        if (is_int($permissions)) {
            return [Permission::query()->findOrFail($permissions)];
        }

        $firstElement = Arr::first((array) $permissions);

        if (is_numeric($firstElement)) {
            $permissionCollection = Permission::query()
                ->whereKey($permissions)
                ->get();
        } else {
            $permissionCollection = Permission::query()
                ->whereIn('name', $permissions)
                ->get();
        }

        if ((is_array($permissions) || $permissions instanceof Countable) &&
            count($permissions) > $permissionCollection->count()
        ) {
            throw (new ModelNotFoundException())->setModel(Permission::class);
        }

        return $permissionCollection->all();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRolePermissions(): EloquentCollection
    {
        $permissionModel = new Permission();
        $roleModel = new Role();

        $permissionRoleTable = Config::get('roles.tables.permission_role');

        return Permission::query()
            ->join(
                $permissionRoleTable,
                "{$permissionRoleTable}.permission_id",
                '=',
                $permissionModel->getQualifiedKeyName()
            )
            ->join(
                $roleModel->getTable(),
                $roleModel->getQualifiedKeyName(),
                '=',
                "{$permissionRoleTable}.role_id"
            )
            ->whereIn($roleModel->getQualifiedKeyName(), $this->getRoles()->modelKeys())
            ->orWhere("{$roleModel->getTable()}.level", '<', $this->levelAccess())
            ->get(["{$permissionModel->getTable()}.*"]);
    }
}
