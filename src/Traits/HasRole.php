<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Traits;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;
use McMatters\LaravelRoles\Events\Role\AttachedRole;
use McMatters\LaravelRoles\Events\Role\AttachingRole;
use McMatters\LaravelRoles\Events\Role\DetachedRole;
use McMatters\LaravelRoles\Events\Role\DetachingRole;
use McMatters\LaravelRoles\Events\Role\SyncedRoles;
use McMatters\LaravelRoles\Events\Role\SyncingRoles;
use McMatters\LaravelRoles\Models\Role;

use function array_map;
use function class_uses;
use function explode;
use function in_array;
use function is_array;
use function is_int;
use function is_numeric;
use function is_string;

use const false;
use const null;
use const true;

trait HasRole
{
    protected ?Collection $roles = null;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function attachRole($role, bool $touch = true): void
    {
        $roles = $this->parseRoles($role);

        Event::dispatch(new AttachingRole($this, $roles));

        $this->roles()->attach($roles, [], $touch);

        Event::dispatch(new AttachedRole($this, $roles));

        $this->flushRoles();
    }

    public function detachRole($role = null, bool $touch = true): void
    {
        if (null !== $role) {
            $role = $this->parseRoles($role);
        }

        Event::dispatch(new DetachingRole($this, $role));

        $this->roles()->detach($role, $touch);

        Event::dispatch(new DetachedRole($this, $role));

        $this->flushRoles();
    }

    public function syncRoles($roles, bool $detaching = true): void
    {
        if (null !== $roles) {
            $roles = $this->parseRoles($roles);
        }

        Event::dispatch(new SyncingRoles($this, $roles));

        $this->roles()->sync($roles, $detaching);

        Event::dispatch(new SyncedRoles($this, $roles));

        $this->flushRoles();
    }

    public function getRoles(): EloquentCollection
    {
        if (null === $this->roles) {
            $this->roles = $this->getRelationValue('roles');
        }

        return $this->roles;
    }

    public function flushRoles(): void
    {
        $this->unsetRelation('roles');
        $this->roles = null;

        if (in_array(HasPermission::class, class_uses($this), true)) {
            $this->flushPermissions();
        }
    }

    public function hasRole($role): bool
    {
        return null !== $this->getRoles()->find($this->parseRole($role));
    }

    public function hasRoles($roles): bool
    {
        foreach ($this->parseRoles($roles) as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    public function hasAnyRole($roles): bool
    {
        foreach ($this->parseRoles($roles) as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function levelAccess(): int
    {
        return (int) ($this->getRoles()->max('level') ?: 0);
    }

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
            return array_map(fn ($role) => $this->parseRole($role), $roles);
        }

        throw new InvalidArgumentException('Invalid roles were passed');
    }
}
