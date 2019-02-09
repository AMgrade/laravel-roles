<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Config;
use McMatters\LaravelRoles\Traits\HasRole;

/**
 * Class Permission
 *
 * @package McMatters\LaravelRoles\Models
 */
class Permission extends Model
{
    use HasRole;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Permission constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('roles.tables.permissions');

        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            Config::get('roles.tables.permission_role')
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            Config::get('roles.models.user'),
            Config::get('roles.tables.permission_user')
        );
    }
}
