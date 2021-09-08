<?php

namespace App\Domain\Product\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidStorageAmountInputException extends InvalidArgumentException
{
    public const STORAGE_AMOUNT_INPUT_IS_INVALID = 'STORAGE AMOUNT INPUT MUST BE NUMERIC AND GREATER THAN 0';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::STORAGE_AMOUNT_INPUT_IS_INVALID, $code, $previous);
    }
}
