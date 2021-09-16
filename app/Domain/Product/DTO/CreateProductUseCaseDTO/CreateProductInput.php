<?php

namespace App\Domain\Product\DTO\CreateProductUseCaseDTO;


use App\Domain\Product\Validator\ProductValidator;
use App\Domain\Product\Validator\ProductValidatorRules;
use App\Models\Product;
use Illuminate\Http\Request;

class CreateProductInput
{
    private Product $product;
    public function __construct(Request $request, ProductValidator $productValidator)
    {
        $productValidator->validateInputs($request, new ProductValidatorRules());
        $this->product = new Product($request->all());
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}
