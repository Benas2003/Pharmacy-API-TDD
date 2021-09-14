<?php

namespace App\Domain\Product\Infrastructure\Database;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductDatabase
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
