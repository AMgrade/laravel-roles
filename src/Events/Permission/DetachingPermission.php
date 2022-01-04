<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Events\Permission;

use Illuminate\Database\Eloquent\Model;

use const null;

/**
 * Class DetachingPermission
 *
 * @package McMatters\LaravelRoles\Events\Permission
 */
class DetachingPermission
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public Model $model;

    /**
     * @var int[]|null
     */
    public ?array $permissions;

    /**
     * DetachingPermission constructor.
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
