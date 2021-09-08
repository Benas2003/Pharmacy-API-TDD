<?php

namespace App\Domain\Product\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidVSSLPRInputException extends InvalidArgumentException
{
    public const VSSLPR_INPUT_INVALID = 'INPUT REQUIRES TO START WITH PREFIX VSSLPR';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::VSSLPR_INPUT_INVALID, $code, $previous);
    }
}
