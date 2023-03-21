<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use McMatters\LaravelRoles\Exceptions\PermissionDeniedException;

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
