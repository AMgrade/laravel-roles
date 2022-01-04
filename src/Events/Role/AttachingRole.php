<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Events\Role;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AttachingRole
 *
 * @package McMatters\LaravelRoles\Events\Role
 */
class AttachingRole
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public Model $model;

    /**
     * @var int[]
     */
    public array $roles;

    /**
     * AttachingRole constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param int[] $roles
     */
    public function __construct(Model $model, array $roles)
    {
        $this->model = $model;
        $this->roles = $roles;
    }
}
