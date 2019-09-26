<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use McMatters\LaravelRoles\Exceptions\RoleDeniedException;

/**
 * Class AnyRole
 *
 * @package McMatters\LaravelRoles\Http\Middleware
 */
class AnyRole
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed $roles
     *
     * @return mixed
     *
     * @throws \McMatters\LaravelRoles\Exceptions\RoleDeniedException
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = $request->user();

        if (!$user || !$user->hasAnyRole($roles)) {
            throw new RoleDeniedException();
        }

        return $next($request);
    }
}
