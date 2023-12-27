<?php

declare(strict_types=1);

namespace AMgrade\Roles\Events\Permission;

use Illuminate\Database\Eloquent\Model;

class AttachingPermission
{
    public Model $model;

    public array $permissions;

    public function __construct(Model $model, array $permissions)
    {
        $this->model = $model;
        $this->permissions = $permissions;
    }
}
