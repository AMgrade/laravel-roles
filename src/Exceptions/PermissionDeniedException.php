<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Exceptions;

use Exception;

/**
 * Class PermissionDeniedException
 *
 * @package McMatters\LaravelRoles\Exceptions
 */
class PermissionDeniedException extends Exception
{
    /**
     * PermissionDeniedException constructor.
     */
    public function __construct()
    {
        parent::__construct('You don\'t have a required permission');
    }
}
