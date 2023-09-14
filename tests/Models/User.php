<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Tests\Models;

use AMgrade\LaravelRoles\Traits\HasPermission;
use AMgrade\LaravelRoles\Traits\HasRole;
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
