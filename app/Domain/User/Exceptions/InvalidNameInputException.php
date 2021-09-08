<?php

namespace App\Domain\User\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidNameInputException extends InvalidArgumentException
{
    public const NAME_INPUT_IS_REQUIRED = 'NAME INPUT IS REQUIRED';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::NAME_INPUT_IS_REQUIRED, $code, $previous);
    }
}
