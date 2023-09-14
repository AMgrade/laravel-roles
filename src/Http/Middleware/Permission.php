<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Http\Middleware;

use AMgrade\LaravelRoles\Exceptions\PermissionDeniedException;
use Closure;
use Illuminate\Http\Request;

use const null;

class Permission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();

        if (null !== $user && $user->hasPermissions($permission)) {
            return $next($request);
        }

        throw new PermissionDeniedException();
    }
}
