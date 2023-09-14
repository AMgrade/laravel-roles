<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Http\Middleware;

use AMgrade\LaravelRoles\Exceptions\RoleDeniedException;
use Closure;
use Illuminate\Http\Request;

use const null;

class AnyRole
{
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = $request->user();

        if (null !== $user && $user->hasAnyRole($roles)) {
            return $next($request);
        }

        throw new RoleDeniedException();
    }
}
