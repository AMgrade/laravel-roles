<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use McMatters\LaravelRoles\Traits\HasPermission;
use McMatters\LaravelRoles\Traits\HasRole;

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
