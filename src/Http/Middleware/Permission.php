<?php

declare(strict_types=1);

namespace AMgrade\Roles\Http\Middleware;

use AMgrade\Roles\Exceptions\PermissionDeniedException;
use Closure;
use Illuminate\Http\Request;

use const null;

class Permission
{
    public function handle(
        Request $request,
        Closure $next,
        $permission,
        ?string $guard = null,
    ) {
        if ($request->user($guard)?->hasPermissions($permission)) {
            return $next($request);
        }

        throw new PermissionDeniedException();
    }
}
