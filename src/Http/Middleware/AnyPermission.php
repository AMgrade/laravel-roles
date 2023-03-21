<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use McMatters\LaravelRoles\Exceptions\PermissionDeniedException;

use const null;

class AnyPermission
{
    public function handle(Request $request, Closure $next, $permissions)
    {
        $user = $request->user();

        if (null !== $user && $user->hasAnyPermission($permissions)) {
            return $next($request);
        }

        throw new PermissionDeniedException();
    }
}
