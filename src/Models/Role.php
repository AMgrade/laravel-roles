<?php

declare(strict_types=1);

namespace AMgrade\Roles\Models;

use AMgrade\Roles\Traits\HasPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Config;

use const null;

class Role extends Model
{
    use HasPermission;

    protected $fillable = [
        'name',
        'level',
    ];

    protected $casts = [
        'name' => 'string',
        'level' => 'int',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('roles.tables.roles');

        parent::__construct($attributes);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            Config::get('roles.tables.permission_role'),
            null,
            null,
            $this->primaryKey,
            null,
            __FUNCTION__,
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            Config::get('roles.models.user'),
            Config::get('roles.tables.role_user'),
            null,
            null,
            $this->primaryKey,
            null,
            __FUNCTION__,
        );
    }
}
