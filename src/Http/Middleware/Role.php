<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use McMatters\LaravelRoles\Exceptions\RoleDeniedException;

use const null;

/**
 * Class Role
 *
 * @package McMatters\LaravelRoles\Http\Middleware
 */
class Role
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed $role
     *
     * @return mixed
     *
     * @throws \McMatters\LaravelRoles\Exceptions\RoleDeniedException
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();

        if (null !== $user && $user->hasRoles($role)) {
            return $next($request);
        }

        throw new RoleDeniedException();
    }
}
