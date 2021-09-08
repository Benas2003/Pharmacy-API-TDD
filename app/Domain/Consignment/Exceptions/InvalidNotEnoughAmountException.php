<?php

namespace App\Domain\Consignment\Exceptions;

use RuntimeException;
use Throwable;

class InvalidNotEnoughAmountException extends RuntimeException
{
    public const NOT_ENOUGH_AMOUNT = 'REQUESTED AMOUNT CANNOT BE GIVEN AWAY BECAUSE PRODUCT AMOUNT IN STORAGE EQUALS 0';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::NOT_ENOUGH_AMOUNT, $code, $previous);
    }
}
