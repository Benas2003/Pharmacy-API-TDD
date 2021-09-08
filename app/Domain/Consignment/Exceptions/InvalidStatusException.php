<?php

namespace App\Domain\Consignment\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidStatusException extends InvalidArgumentException
{
    public const WRONG_STATUS = 'THIS ACTION CANNOT BE PERFORMED WHEN CONSIGNMENT ENTERS PROCESSED STATUS';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::WRONG_STATUS, $code, $previous);
    }
}
