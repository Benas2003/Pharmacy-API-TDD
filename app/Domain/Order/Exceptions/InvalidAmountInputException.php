<?php

namespace App\Domain\Order\Exceptions;

use RuntimeException;
use Throwable;

class InvalidAmountInputException extends RuntimeException
{
    public const AMOUNT_INPUT_IS_REQUIRED = 'AMOUNT INPUT MUST BE NUMERIC AND GREATER THAN 0';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::AMOUNT_INPUT_IS_REQUIRED, $code, $previous);
    }
}
