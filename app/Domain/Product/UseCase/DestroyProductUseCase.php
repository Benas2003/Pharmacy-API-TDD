<?php

namespace App\Domain\Product\UseCase;

use App\Domain\Product\Validator\ProductValidator;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DestroyProductUseCase
{
    public function execute(int $id): JsonResponse
    {
        Product::destroy($id);

        return new JsonResponse(null, ResponseAlias::HTTP_NO_CONTENT);
    }

}
