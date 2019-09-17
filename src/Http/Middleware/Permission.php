<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use McMatters\LaravelRoles\Exceptions\PermissionDeniedException;

/**
 * Class Permission
 *
 * @package McMatters\LaravelRoles\Http\Middleware
 */
class Permission
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed $permission
     *
     * @return mixed
     *
     * @throws \McMatters\LaravelRoles\Exceptions\PermissionDeniedException
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();

        if (!$user || !$user->hasPermissions($permission)) {
            throw new PermissionDeniedException();
        }

        return $next($request);
    }
}
