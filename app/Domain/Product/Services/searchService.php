<?php

namespace App\Domain\Product\Services;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class searchService
{
    public function searchByName(string $name): JsonResponse
    {
        return new JsonResponse(Product::where('name', 'like', '%'.$name.'%')->get());
    }

    public function searchByCode(string $code): JsonResponse
    {
        return new JsonResponse(Product::select('id', 'VSSLPR', 'name')->where('VSSLPR', 'like', '%'.$code.'%')->get());
    }
}
