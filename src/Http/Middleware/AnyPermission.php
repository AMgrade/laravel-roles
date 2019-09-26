<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use McMatters\LaravelRoles\Exceptions\PermissionDeniedException;

/**
 * Class AnyPermission
 *
 * @package McMatters\LaravelRoles\Http\Middleware
 */
class AnyPermission
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed $permissions
     *
     * @return mixed
     *
     * @throws \McMatters\LaravelRoles\Exceptions\PermissionDeniedException
     */
    public function handle(Request $request, Closure $next, $permissions)
    {
        $user = $request->user();

        if (!$user || !$user->hasAnyPermission($permissions)) {
            throw new PermissionDeniedException();
        }

        return $next($request);
    }
}
