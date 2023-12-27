<?php

declare(strict_types=1);

namespace AMgrade\Roles\Http\Middleware;

use AMgrade\Roles\Exceptions\RoleDeniedException;
use Closure;
use Illuminate\Http\Request;

use const null;

class Role
{
    public function handle(
        Request $request,
        Closure $next,
        $role,
        ?string $guard = null
    ) {
        if ($request->user($guard)?->hasRoles($role)) {
            return $next($request);
        }

        throw new RoleDeniedException();
    }
}
