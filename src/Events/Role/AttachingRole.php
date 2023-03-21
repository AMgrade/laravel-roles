<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Events\Role;

use Illuminate\Database\Eloquent\Model;

class AttachingRole
{
    public Model $model;

    public array $roles;

    public function __construct(Model $model, array $roles)
    {
        $this->model = $model;
        $this->roles = $roles;
    }
}
