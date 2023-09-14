<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Http\Middleware;

use AMgrade\LaravelRoles\Exceptions\LevelAccessDeniedException;
use Closure;
use Illuminate\Http\Request;

use const null;

class Level
{
    public function handle(Request $request, Closure $next, $level)
    {
        $user = $request->user();

        if (null !== $user && $user->levelAccess >= $level) {
            return $next($request);
        }

        throw new LevelAccessDeniedException();
    }
}
