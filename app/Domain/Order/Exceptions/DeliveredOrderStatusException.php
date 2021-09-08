<?php

namespace App\Domain\Order\Exceptions;

use RuntimeException;
use Throwable;

class DeliveredOrderStatusException extends RuntimeException
{
    public const STATUS_CANNOT_BE_DELIVERED = 'CANNOT CHANGE AMOUNT WHEN ORDER IS ALREADY DELIVERED';

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::STATUS_CANNOT_BE_DELIVERED, $code, $previous);
    }
}
