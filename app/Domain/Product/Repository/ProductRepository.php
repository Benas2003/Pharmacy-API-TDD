<?php

namespace App\Domain\Product\Repository;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductRepository
{
    public function getAllProducts(): JsonResponse
    {
        return new JsonResponse(Product::all());
    }

    public function getProductById(int $id): JsonResponse
    {
        return new JsonResponse(Product::findOrFail($id));
    }
}
