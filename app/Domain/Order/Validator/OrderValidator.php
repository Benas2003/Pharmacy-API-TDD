<?php

namespace App\Domain\Order\Validator;

use App\Domain\Order\Exceptions\InvalidAmountInputException;

class OrderValidator
{
    public function validateAmount(int $amount): void
    {
        if($amount <= 0)
        {
            throw new InvalidAmountInputException();
        }
    }
}
