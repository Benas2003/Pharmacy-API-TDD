<?php

namespace App\Domain\Product\DTO\UpdateProductUseCaseDTO;

use App\Models\Product;

class UpdateProductOutput
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    public function toArray(): array
    {
        return $this->product->toArray();
    }
}
