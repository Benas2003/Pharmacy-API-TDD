<?php

namespace App\Domain\Product\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductValidatorRules
{
    public function vsslprValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'VSSLPR' => 'required|starts_with:VSSLPR',
        ]);
    }

    public function nameValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => 'required',
        ]);
    }

    public function storageAmountValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'storage_amount' => 'required|numeric|gt:0',
        ]);
    }

    public function priceValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'price' => 'required|numeric|gt:0',
        ]);
    }
}
