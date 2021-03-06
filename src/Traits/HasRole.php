<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Traits;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use McMatters\LaravelRoles\Models\Role;
use const false, null, true;
use function array_map, class_uses, explode, in_array, is_array, is_int, is_numeric, is_string;

/**
 * Trait HasRole
 *
 * @package McMatters\LaravelRoles\Traits
 */
trait HasRole
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $roles;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param mixed $role
     * @param bool $touch
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    public function attachRole($role, bool $touch = true): void
    {
        $this->roles()->attach($this->parseRoles($role), [], $touch);

        $this->flushRoles();
    }

    /**
     * @param mixed $role
     * @param bool $touch
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    public function detachRole($role = null, bool $touch = true): void
    {
        if (null !== $role) {
            $role = $this->parseRoles($role);
        }

        $this->roles()->detach($role, $touch);

        $this->flushRoles();
    }

    /**
     * @param mixed $roles
     * @param bool $detaching
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    public function syncRoles($roles, bool $detaching = true): void
    {
        $this->roles()->sync($this->parseRoles($roles), $detaching);

        $this->flushRoles();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRoles(): EloquentCollection
    {
        if (null === $this->roles) {
            $this->roles = $this->getAttribute('roles');
        }

        return $this->roles;
    }

    /**
     * @return void
     */
    public function flushRoles(): void
    {
        $this->unsetRelation('roles');
        $this->roles = null;

        if (in_array(HasPermission::class, class_uses($this), true)) {
            $this->flushPermissions();
        }
    }

    /**
     * @param mixed $role
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    public function hasRole($role): bool
    {
        return null !== $this->getRoles()->find($this->parseRole($role));
    }

    /**
     * @param mixed $roles
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    public function hasRoles($roles): bool
    {
        foreach ($this->parseRoles($roles) as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $roles
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    public function hasAnyRole($roles): bool
    {
        foreach ($this->parseRoles($roles) as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public function levelAccess(): int
    {
        return (int) ($this->getRoles()->max('level') ?: 0);
    }

    /**
     * @param int|string|\McMatters\LaravelRoles\Models\Role $role
     *
     * @return int
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    protected function parseRole($role): int
    {
        if (is_int($role) || is_numeric($role)) {
            return (int) $role;
        }

        if (is_string($role)) {
            return Role::query()
                ->where('name', $role)
                ->firstOrFail()
                ->getKey();
        }

        if ($role instanceof Role) {
            return $role->getKey();
        }

        throw new InvalidArgumentException('Invalid role was passed');
    }

    /**
     * @param mixed $roles
     *
     * @return array
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    protected function parseRoles($roles): array
    {
        if (is_int($roles)) {
            return [$roles];
        }

        if ($roles instanceof Role) {
            return [$roles->getKey()];
        }

        if ($roles instanceof Collection) {
            $roles = $roles->all();
        }

        if (is_string($roles)) {
            $roles = explode('|', $roles);
        }

        if (is_array($roles)) {
            return array_map(function ($role) {
                return $this->parseRole($role);
            }, $roles);
        }

        throw new InvalidArgumentException('Invalid roles were passed');
    }
}
