<?php

namespace App\Domain\User\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidPasswordInputException extends InvalidArgumentException
{
    public const PASSWORD_INPUT_IS_REQUIRED = 'PASSWORD MUST BE EQUAL TO PASSWORD-REPEAT';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::PASSWORD_INPUT_IS_REQUIRED, $code, $previous);
    }
}
