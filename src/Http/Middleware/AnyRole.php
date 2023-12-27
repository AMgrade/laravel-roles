<?php

declare(strict_types=1);

namespace AMgrade\Roles\Http\Middleware;

use AMgrade\Roles\Exceptions\RoleDeniedException;
use Closure;
use Illuminate\Http\Request;

use const null;

class AnyRole
{
    public function handle(
        Request $request,
        Closure $next,
        $roles,
        ?string $guard = null,
    ) {
        if ($request->user($guard)?->hasAnyRole($roles)) {
            return $next($request);
        }

        throw new RoleDeniedException();
    }
}
