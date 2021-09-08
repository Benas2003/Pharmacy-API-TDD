<?php

namespace App\Domain\Product\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidPriceInputException extends InvalidArgumentException
{
    public const PRICE_INPUT_IS_REQUIRED = 'PRICE INPUT MUST BE NUMERIC AND GREATER THAN 0';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::PRICE_INPUT_IS_REQUIRED, $code, $previous);
    }
}
