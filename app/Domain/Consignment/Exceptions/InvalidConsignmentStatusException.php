<?php

namespace App\Domain\Consignment\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidConsignmentStatusException extends InvalidArgumentException
{
    public const WRONG_CONSIGNMENT_STATUS = 'CANNOT DELETE CONSIGNMENT IF IT IS NOT IN CREATED STATUS';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::WRONG_CONSIGNMENT_STATUS, $code, $previous);
    }
}
