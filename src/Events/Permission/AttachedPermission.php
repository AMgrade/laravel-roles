<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Events\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AttachedPermission
 *
 * @package McMatters\LaravelRoles\Events\Permission
 */
class AttachedPermission
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * @var int[]
     */
    public $permissions;

    /**
     * AttachedPermission constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param int[] $permissions
     */
    public function __construct(Model $model, array $permissions)
    {
        $this->model = $model;
        $this->permissions = $permissions;
    }
}
