<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\Validator\ProductValidator;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateProductUseCase
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(int $id): JsonResponse
    {
        $productValidator = new ProductValidator();
        $productValidator->validateInputs($this->request);

        $product = Product::findOrFail($id);
        $product->update($this->request->all());

        return new JsonResponse($product);
    }
}
