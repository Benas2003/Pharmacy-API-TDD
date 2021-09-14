<?php

namespace App\Domain\Product\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class SearchService
{
    public function searchByName(string $name): Collection
    {
        return Product::where('name', 'like', '%'.$name.'%')->get();
    }

    public function searchByCode(string $code): Collection
    {
        return Product::select('id', 'VSSLPR', 'name')->where('VSSLPR', 'like', '%'.$code.'%')->get();
    }
}
