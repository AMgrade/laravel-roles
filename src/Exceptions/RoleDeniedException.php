<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Exceptions;

use Exception;

class RoleDeniedException extends Exception
{
    public function __construct()
    {
        parent::__construct('You don\'t have a required role');
    }
}
