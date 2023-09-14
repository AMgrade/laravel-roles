<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Exceptions;

use Exception;

class PermissionDeniedException extends Exception
{
    public function __construct()
    {
        parent::__construct('You don\'t have a required permission');
    }
}
