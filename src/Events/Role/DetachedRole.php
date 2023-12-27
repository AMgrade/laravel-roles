<?php

declare(strict_types=1);

namespace AMgrade\Roles\Events\Role;

use Illuminate\Database\Eloquent\Model;

use const null;

class DetachedRole
{
    public Model $model;

    public ?array $roles;

    public function __construct(Model $model, ?array $roles = null)
    {
        $this->model = $model;
        $this->roles = $roles;
    }
}
