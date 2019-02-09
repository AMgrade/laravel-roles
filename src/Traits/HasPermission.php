<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Traits;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use McMatters\LaravelRoles\Models\Permission;
use McMatters\LaravelRoles\Models\Role;
use const false, null, true;
use function class_uses, in_array, is_int, is_numeric, is_string;

/**
 * Trait HasPermission
 *
 * @package McMatters\LaravelRoles\Traits
 */
trait HasPermission
{
    /**
     * @var EloquentCollection|null
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
     * @return EloquentCollection
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
     */
    protected function parsePermissions($permissions, bool $load = false): array
    {
        if ($permissions instanceof Collection) {
            return $permissions->all();
        }

        if (is_string($permissions)) {
            $permissions = explode('|', $permissions);
        }

        if (!$load || $permissions instanceof Permission) {
            return Arr::wrap($permissions);
        }

        $firstElement = Arr::first((array) $permissions);

        if (is_numeric($firstElement)) {
            return Permission::query()->whereKey($permissions)->get()->all();
        }

        return Permission::query()->whereIn('name', $permissions)->get()->all();
    }

    /**
     * @return EloquentCollection
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
