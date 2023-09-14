<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Events\Permission;

use Illuminate\Database\Eloquent\Model;

class AttachedPermission
{
    public Model $model;

    public array $permissions;

    public function __construct(Model $model, array $permissions)
    {
        $this->model = $model;
        $this->permissions = $permissions;
    }
}
