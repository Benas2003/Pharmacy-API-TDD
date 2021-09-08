<?php

namespace App\Domain\User\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidRoleInputException extends InvalidArgumentException
{
    public const ROLE_MUST_BE_SELECTED_FROM_LIST = 'ROLE MUST BE SELECTED FROM THESE OPTIONS: ADMINISTRATOR, PHARMACIST, DEPARTMENT';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::ROLE_MUST_BE_SELECTED_FROM_LIST, $code, $previous);
    }
}
