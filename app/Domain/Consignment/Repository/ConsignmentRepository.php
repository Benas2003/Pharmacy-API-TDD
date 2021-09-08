<?php

namespace App\Domain\Consignment\Repository;

use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Illuminate\Http\JsonResponse;

class ConsignmentRepository
{
    public function getAllProducts(): JsonResponse
    {
        return new JsonResponse(Consignment::all());
    }

    public function getProductById(int $id): JsonResponse
    {
        $consignment = Consignment::findOrFail($id);
        $consignment_products = ConsignmentProduct::where('consignment_id',$consignment->id)->get();

        $data = [
            'Status'=>$consignment->status,
            'Products:'=>$consignment_products,
        ];

        return new JsonResponse($data);
    }
}
