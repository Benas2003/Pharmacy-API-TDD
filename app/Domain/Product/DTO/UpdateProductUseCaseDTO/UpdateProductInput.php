<?php

namespace App\Domain\Product\DTO\UpdateProductUseCaseDTO;

use App\Domain\Product\Validator\ProductValidator;
use App\Domain\Product\Validator\ProductValidatorRules;
use App\Models\Product;
use Illuminate\Http\Request;

class UpdateProductInput
{
    private int $id;
    private Product $product;

    public function __construct(int $id, Request $request, ProductValidator $productValidator)
    {
        $productValidator->validateInputs($request, new ProductValidatorRules());

        $this->product = new Product($request->all());
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}
