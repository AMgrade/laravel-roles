<?php

declare(strict_types=1);

namespace AMgrade\Roles\Http\Middleware;

use AMgrade\Roles\Exceptions\LevelAccessDeniedException;
use Closure;
use Illuminate\Http\Request;

use const null;

class Level
{
    public function handle(
        Request $request,
        Closure $next,
        int $level,
        ?string $guard = null,
    ) {
        if ($request->user($guard)?->levelAccess() >= $level) {
            return $next($request);
        }

        throw new LevelAccessDeniedException();
    }
}
