<?php

namespace App\Domain\Product\Repository;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository
{
    public function getAllProducts(): Collection
    {
        return Product::all();
    }

    public function getProductById(int $id): Product
    {
        return Product::findOrFail($id);
    }
}
