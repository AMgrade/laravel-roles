<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Events\Permission;

use Illuminate\Database\Eloquent\Model;

use const null;

class DetachedPermission
{
    public Model $model;

    public ?array $permissions;

    public function __construct(Model $model, array $permissions = null)
    {
        $this->model = $model;
        $this->permissions = $permissions;
    }
}
