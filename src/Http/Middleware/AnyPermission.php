<?php

declare(strict_types=1);

namespace AMgrade\Roles\Http\Middleware;

use AMgrade\Roles\Exceptions\PermissionDeniedException;
use Closure;
use Illuminate\Http\Request;

use const null;

class AnyPermission
{
    public function handle(
        Request $request,
        Closure $next,
        $permissions,
        ?string $guard = null,
    ) {
        if ($request->user($guard)?->hasAnyPermission($permissions)) {
            return $next($request);
        }

        throw new PermissionDeniedException();
    }
}
