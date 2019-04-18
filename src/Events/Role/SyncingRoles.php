<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Events\Role;

use Illuminate\Database\Eloquent\Model;
use const null;

/**
 * Class SyncingRoles
 *
 * @package McMatters\LaravelRoles\Events\Role
 */
class SyncingRoles
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * @var int[]|null
     */
    public $roles;

    /**
     * SyncingRoles constructor.
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
