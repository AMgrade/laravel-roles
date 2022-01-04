<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Events\Role;

use Illuminate\Database\Eloquent\Model;

use const null;

/**
 * Class SyncedRoles
 *
 * @package McMatters\LaravelRoles\Events\Role
 */
class SyncedRoles
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public Model $model;

    /**
     * @var int[]|null
     */
    public ?array $roles;

    /**
     * SyncedRoles constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array|null $roles
     */
    public function __construct(Model $model, array $roles = null)
    {
        $this->model = $model;
        $this->roles = $roles;
    }
}
