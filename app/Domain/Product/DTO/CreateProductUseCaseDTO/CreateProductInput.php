<?php

namespace App\Domain\Product\DTO\CreateProductUseCaseDTO;


use App\Domain\Product\Validator\ProductValidator;
use App\Models\Product;
use Illuminate\Http\Request;

class CreateProductInput
{
    private Product $product;


    public function __construct(Request $request)
    {
        $productValidator = new ProductValidator();
        $productValidator->validateInputs($request);
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
