<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\DTO\CreateProductUseCaseDTO\CreateProductInput;
use App\Domain\Product\DTO\CreateProductUseCaseDTO\CreateProductOutput;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CreateProductUseCase
{
    public function execute(CreateProductInput $createProductInput): CreateProductOutput
    {
        $product = Product::create($createProductInput->getProduct()->toArray());

        return new CreateProductOutput($product);
//        return new JsonResponse($product, ResponseAlias::HTTP_CREATED);
    }

}
