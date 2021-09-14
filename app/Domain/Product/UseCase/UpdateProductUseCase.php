<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\DTO\UpdateProductUseCaseDTO\UpdateProductInput;
use App\Domain\Product\DTO\UpdateProductUseCaseDTO\UpdateProductOutput;
use App\Models\Product;

class UpdateProductUseCase
{
    public function execute(UpdateProductInput $updateProductInput): UpdateProductOutput
    {
        $product = Product::findOrFail($updateProductInput->getId());
        $product->update($updateProductInput->getProduct()->toArray());

        return new UpdateProductOutput($product);
    }
}
