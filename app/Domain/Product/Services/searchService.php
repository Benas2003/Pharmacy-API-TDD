<?php

namespace App\Domain\Product\Services;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class searchService
{
    public function execute(string $name): JsonResponse
    {
        return new JsonResponse(Product::where('name', 'like', '%'.$name.'%')->get());
    }
}
