<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Config;
use McMatters\LaravelRoles\Traits\HasPermission;

use const null;

/**
 * Class Role
 *
 * @package McMatters\LaravelRoles\Models
 */
class Role extends Model
{
    use HasPermission;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'level' => 'int',
    ];

    /**
     * Role constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('roles.tables.roles');

        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            Config::get('roles.tables.permission_role'),
            null,
            null,
            $this->primaryKey,
            null,
            __FUNCTION__
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            Config::get('roles.models.user'),
            Config::get('roles.tables.role_user'),
            null,
            null,
            $this->primaryKey,
            null,
            __FUNCTION__
        );
    }
}
