<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use McMatters\LaravelRoles\Exceptions\LevelAccessDeniedException;

/**
 * Class Level
 *
 * @package McMatters\LaravelRoles\Http\Middleware
 */
class Level
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int|string $level
     *
     * @return mixed
     * @throws \McMatters\LaravelRoles\Exceptions\LevelAccessDeniedException
     */
    public function handle(Request $request, Closure $next, $level)
    {
        $user = $request->user();

        if (!$user || $user->levelAccess < $level) {
            throw new LevelAccessDeniedException();
        }

        return $next($request);
    }
}
