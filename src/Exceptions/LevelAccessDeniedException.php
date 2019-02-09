<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Exceptions;

use Exception;

/**
 * Class LevelAccessDeniedException
 *
 * @package McMatters\LaravelRoles\Exceptions
 */
class LevelAccessDeniedException extends Exception
{
    /**
     * LevelAccessDeniedException constructor.
     */
    public function __construct()
    {
        parent::__construct('You don\'t have a required level access');
    }
}
