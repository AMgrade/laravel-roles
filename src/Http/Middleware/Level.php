<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use McMatters\LaravelRoles\Exceptions\LevelAccessDeniedException;

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
