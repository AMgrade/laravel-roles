<?php

declare(strict_types=1);

namespace AMgrade\Roles\Events\Role;

use Illuminate\Database\Eloquent\Model;

class AttachedRole
{
    public Model $model;

    public array $roles;

    public function __construct(Model $model, array $roles)
    {
        $this->model = $model;
        $this->roles = $roles;
    }
}
