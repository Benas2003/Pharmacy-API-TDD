<?php

namespace App\Domain\Product\Validator;

use App\Domain\Product\Exceptions\InvalidNameInputException;
use App\Domain\Product\Exceptions\InvalidPriceInputException;
use App\Domain\Product\Exceptions\InvalidStorageAmountInputException;
use App\Domain\Product\Exceptions\InvalidVSSLPRInputException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductValidator
{


    public function validateInputs(Request $request): void
    {
        $vsslprValidator = $this->vsslprValidator($request);

        $nameValidator = $this->nameValidator($request);

        $storageAmountValidator = $this->storageAmountValidator($request);

        $priceValidator = $this->priceValidator($request);

        if ($vsslprValidator->fails()) {
            throw new InvalidVSSLPRInputException();
        }

        if ($nameValidator->fails()) {
            throw new InvalidNameInputException();
        }

        if ($storageAmountValidator->fails()) {
            throw new InvalidStorageAmountInputException();
        }

        if ($priceValidator->fails()) {
            throw new InvalidPriceInputException();
        }

    }

    protected function vsslprValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {

        return Validator::make($request->all(), [
            'VSSLPR' => 'required|starts_with:VSSLPR',
        ]);
    }

    protected function nameValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => 'required',
        ]);
    }

    protected function storageAmountValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'storage_amount' => 'required|numeric|gt:0',
        ]);
    }

    protected function priceValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'price' => 'required|numeric|gt:0',
        ]);
    }
}
