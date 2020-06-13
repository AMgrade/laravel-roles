<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Events\Permission;

use Illuminate\Database\Eloquent\Model;

use const null;

/**
 * Class DetachedPermission
 *
 * @package McMatters\LaravelRoles\Events\Permission
 */
class DetachedPermission
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * @var int[]|null
     */
    public $permissions;

    /**
     * DetachedPermission constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param int[]|null $permissions
     */
    public function __construct(Model $model, array $permissions = null)
    {
        $this->model = $model;
        $this->permissions = $permissions;
    }
}
