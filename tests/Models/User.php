<?php

declare(strict_types=1);

namespace AMgrade\Roles\Tests\Models;

use AMgrade\Roles\Traits\HasPermission;
use AMgrade\Roles\Traits\HasRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use HasPermission;
    use HasRole;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
