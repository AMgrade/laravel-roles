<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Events\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AttachingPermission
 *
 * @package McMatters\LaravelRoles\Events\Permission
 */
class AttachingPermission
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public Model $model;

    /**
     * @var int[]
     */
    public array $permissions;

    /**
     * AttachingPermission constructor.
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
