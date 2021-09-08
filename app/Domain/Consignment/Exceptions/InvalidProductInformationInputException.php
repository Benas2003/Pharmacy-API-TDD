<?php

namespace App\Domain\Consignment\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidProductInformationInputException extends InvalidArgumentException
{
    public const WRONG_CREDENTIALS = 'ENTERED PRODUCT AMOUNT/ID IS INCORRECT';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::WRONG_CREDENTIALS, $code, $previous);
    }
}
