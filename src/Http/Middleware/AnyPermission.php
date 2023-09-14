<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Http\Middleware;

use AMgrade\LaravelRoles\Exceptions\PermissionDeniedException;
use Closure;
use Illuminate\Http\Request;

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
