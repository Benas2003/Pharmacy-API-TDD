<?php

namespace App\Domain\Consignment\Repository;

use App\Models\ConsignmentProduct;
use Illuminate\Support\Collection;

class ConsignmentProductRepository
{
    public function getConsignmentProductById(int $id): ConsignmentProduct
    {
        return ConsignmentProduct::findOrFail($id);
    }

    public function getAllProductsByConsignmentId(int $id): Collection
    {
        return ConsignmentProduct::where('consignment_id', $id)->get();
    }
}
