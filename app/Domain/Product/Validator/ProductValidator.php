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

    public function validateInputs(Request $request, ProductValidatorRules $productValidatorRules): void
    {
        $vsslprValidator = $productValidatorRules->vsslprValidator($request);

        $nameValidator = $productValidatorRules->nameValidator($request);

        $storageAmountValidator = $productValidatorRules->storageAmountValidator($request);

        $priceValidator = $productValidatorRules->priceValidator($request);

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
}
