<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Http\Middleware;

use AMgrade\LaravelRoles\Exceptions\RoleDeniedException;
use Closure;
use Illuminate\Http\Request;

use const null;

class Role
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();

        if (null !== $user && $user->hasRoles($role)) {
            return $next($request);
        }

        throw new RoleDeniedException();
    }
}
