<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\Validator\ProductValidator;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CreateProductUseCase
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(): JsonResponse
    {
        $productValidator = new ProductValidator();
        $productValidator->validateInputs($this->request);

        $product = Product::create($this->request->all());

        return new JsonResponse($product, ResponseAlias::HTTP_CREATED);
    }

}
