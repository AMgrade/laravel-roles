<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use McMatters\LaravelRoles\Exceptions\RoleDeniedException;

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
