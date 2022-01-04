<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Events\Role;

use Illuminate\Database\Eloquent\Model;

use const null;

/**
 * Class DetachedRole
 *
 * @package McMatters\LaravelRoles\Events\Role
 */
class DetachedRole
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
     * DetachedRole constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param int[]|null $roles
     */
    public function __construct(Model $model, array $roles = null)
    {
        $this->model = $model;
        $this->roles = $roles;
    }
}
