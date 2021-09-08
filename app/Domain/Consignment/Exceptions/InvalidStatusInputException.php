<?php

namespace App\Domain\Consignment\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidStatusInputException extends InvalidArgumentException
{
    public const STATUS_MUST_BE_SELECTED_FROM_LIST = 'STATUS MUST BE SELECTED FROM THESE OPTIONS: CREATED, PROCESSED, GIVEN AWAY';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::STATUS_MUST_BE_SELECTED_FROM_LIST, $code, $previous);
    }
}
