<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Traits;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use McMatters\LaravelRoles\Events\Permission\AttachedPermission;
use McMatters\LaravelRoles\Events\Permission\AttachingPermission;
use McMatters\LaravelRoles\Events\Permission\DetachedPermission;
use McMatters\LaravelRoles\Events\Permission\DetachingPermission;
use McMatters\LaravelRoles\Events\Permission\SyncedPermissions;
use McMatters\LaravelRoles\Events\Permission\SyncingPermissions;
use McMatters\LaravelRoles\Models\Permission;
use McMatters\LaravelRoles\Models\Role;

use function class_uses;
use function count;
use function in_array;
use function is_countable;
use function is_int;
use function is_numeric;
use function is_string;

use const false;
use const null;
use const true;

trait HasPermission
{
    protected ?Collection $permissions = null;

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            null,
            null,
            null,
            $this->primaryKey,
            null,
            __FUNCTION__,
        );
    }

    public function attachPermission($permission, bool $touch = true): void
    {
        $permissions = (new EloquentCollection())
            ->merge($this->parsePermissions($permission, true))
            ->modelKeys();

        Event::dispatch(new AttachingPermission($this, $permissions));

        $this->permissions()->attach($permissions, [], $touch);

        Event::dispatch(new AttachedPermission($this, $permissions));

        $this->flushPermissions();
    }

    public function detachPermission(
        $permission = null,
        bool $touch = true
    ): void {
        if (null !== $permission) {
            $permission = (new EloquentCollection())
                ->merge($this->parsePermissions($permission, true))
                ->modelKeys();
        }

        Event::dispatch(new DetachingPermission($this, $permission));

        $this->permissions()->detach($permission, $touch);

        Event::dispatch(new DetachedPermission($this, $permission));

        $this->flushPermissions();
    }

    public function syncPermissions($permissions, bool $detaching = true): void
    {
        if (null !== $permissions) {
            $permissions = (new EloquentCollection())
                ->merge($this->parsePermissions($permissions, true))
                ->modelKeys();
        }

        Event::dispatch(new SyncingPermissions($this, $permissions));

        $this->permissions()->sync($permissions, $detaching);

        Event::dispatch(new SyncedPermissions($this, $permissions));

        $this->flushPermissions();
    }

    public function getPermissions(): EloquentCollection
    {
        if (null === $this->permissions) {
            /** @var \Illuminate\Database\Eloquent\Collection $permissions */
            $permissions = $this->getRelationValue('permissions');

            if (
                $this instanceof Role ||
                in_array(HasRole::class, class_uses($this), true)
            ) {
                $permissions = $permissions->merge($this->getRolePermissions());
            }

            $this->permissions = $permissions;
        }

        return $this->permissions;
    }

    public function flushPermissions(): void
    {
        $this->unsetRelation('permissions');
        $this->permissions = null;
    }

    public function hasPermission($permission): bool
    {
        return $this->getPermissions()->contains(
            static function (Permission $model) use ($permission) {
                if (is_int($permission) || is_numeric($permission)) {
                    return (int) $permission === $model->getKey();
                }

                if (is_string($permission)) {
                    return $model->getAttribute('name') === $permission;
                }

                return $permission instanceof Permission && $model->is($permission);
            }
        );
    }

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

    protected function parsePermissions($permissions, bool $load = false): array
    {
        if ($permissions instanceof Collection) {
            return $permissions->map(
                static function ($permission) {
                    if (is_int($permission)) {
                        return Permission::query()->findOrFail($permission);
                    }

                    if (is_string($permission)) {
                        return Permission::query()
                            ->where('name', $permission)
                            ->firstOrFail();
                    }

                    return $permission;
                }
            )->all();
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
        } elseif ($firstElement instanceof Permission) {
            $permissionCollection = (new EloquentCollection())->merge(
                (array) $permissions,
            );
        } else {
            $permissionCollection = Permission::query()
                ->whereIn('name', $permissions)
                ->get();
        }

        if (
            is_countable($permissions) &&
            count($permissions) > $permissionCollection->count()
        ) {
            throw (new ModelNotFoundException())->setModel(Permission::class);
        }

        return $permissionCollection->all();
    }

    protected function getRolePermissions(): EloquentCollection
    {
        $isThisRole = $this instanceof Role;

        $permissionModel = new Permission();
        $roleModel = $isThisRole ? $this : new Role();

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
            ->whereIn(
                $roleModel->getQualifiedKeyName(),
                $isThisRole ? [$this->getKey()] : $this->getRoles()->modelKeys()
            )
            ->orWhere(
                "{$roleModel->getTable()}.level",
                '<',
                $isThisRole ? $this->getAttribute('level') : $this->levelAccess()
            )
            ->get(["{$permissionModel->getTable()}.*"]);
    }
}
