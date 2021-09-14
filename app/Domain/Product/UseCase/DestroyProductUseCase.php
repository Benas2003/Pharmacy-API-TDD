<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\DTO\DestroyProductUseCaseDTO\DestroyProductInput;
use App\Models\Product;

class DestroyProductUseCase
{
    public function execute(DestroyProductInput $destroyProductInput): void
    {
        Product::destroy($destroyProductInput->getId());
    }

}
