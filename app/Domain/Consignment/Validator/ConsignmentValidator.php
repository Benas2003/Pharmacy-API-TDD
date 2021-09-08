<?php

namespace App\Domain\Consignment\Validator;

use App\Domain\Consignment\Exceptions\InvalidAmountInputException;
use App\Domain\Consignment\Exceptions\InvalidStatusInputException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConsignmentValidator
{

    public function validateStatus(Request $request): void
    {
        $statusValidator = $this->statusValidator($request);

        if ($statusValidator->fails()) {
            throw new InvalidStatusInputException();
        }
    }

    public function validateConsignmentProductAmount(Request $request): void
    {
        $amountValidator = $this->amountValidator($request);

        if ($amountValidator->fails()) {
            throw new InvalidAmountInputException();
        }
    }


    public function validateAmount(mixed $requested_product): bool
    {
        return $requested_product['amount'] > 0 && is_int($requested_product['amount']) && !empty($requested_product['amount']);
    }

    public function validateId(mixed $requested_product): bool
    {
        return $requested_product['id'] > 0 && is_int($requested_product['id']) && !empty($requested_product['id']);
    }


    protected function statusValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'status'=>[
                'required',
                Rule::in(['Created', 'Processed', 'Given away']),
            ],
        ]);
    }

    protected function amountValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'amount'=>'required|numeric|gt:0',
        ]);
    }
}
