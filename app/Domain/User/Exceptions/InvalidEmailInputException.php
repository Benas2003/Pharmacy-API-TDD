<?php

namespace App\Domain\User\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidEmailInputException extends InvalidArgumentException
{
    public const EMAIL_INPUT_IS_REQUIRED = 'EMAIL INPUT IS REQUIRED AND MUST BE UNIQUE';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::EMAIL_INPUT_IS_REQUIRED, $code, $previous);
    }
}
