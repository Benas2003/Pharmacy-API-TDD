<?php

namespace App\Domain\User\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidCredentialsInputException extends InvalidArgumentException
{
    public const WRONG_CREDENTIALS = 'ENTERED CREDENTIALS IS INCORRECT';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::WRONG_CREDENTIALS, $code, $previous);
    }
}
