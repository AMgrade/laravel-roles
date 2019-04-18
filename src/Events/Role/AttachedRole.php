<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Events\Role;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AttachedRole
 *
 * @package McMatters\LaravelRoles\Events\Role
 */
class AttachedRole
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * @var int[]
     */
    public $roles;

    /**
     * AttachedRole constructor.
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
