<?php

namespace App\Domain\Product\DTO\UpdateProductUseCaseDTO;

use App\Domain\Product\Validator\ProductValidator;
use App\Models\Product;
use Illuminate\Http\Request;

class UpdateProductInput
{
    private int $id;
    private Product $product;

    public function __construct(int $id, Request $request)
    {
        $productValidator = new ProductValidator();
        $productValidator->validateInputs($request);

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
