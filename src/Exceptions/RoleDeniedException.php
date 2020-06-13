<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Exceptions;

use Exception;

/**
 * Class RoleDeniedException
 *
 * @package McMatters\LaravelRoles\Exceptions
 */
class RoleDeniedException extends Exception
{
    /**
     * RoleDeniedException constructor.
     */
    public function __construct()
    {
        parent::__construct('You don\'t have a required role');
    }
}
