<?php

namespace App\Domain\Consignment\Repository;

use App\Domain\Consignment\DTO\ConsignmentRepositoryDTO\getConsignmentByIdDTO;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Illuminate\Support\Collection;

class ConsignmentRepository
{
    public function getAllConsignments(): Collection
    {
        return Consignment::all();
    }

    public function getConsignmentById(int $id): GetConsignmentByIdDTO
    {
        $consignment = Consignment::findOrFail($id);
        $consignment_products = ConsignmentProduct::where('consignment_id',$consignment->id)->get();

        return new GetConsignmentByIdDTO($consignment->status, $consignment_products);
    }

    public function getSpecificConsignment(int $id): Consignment
    {
        return Consignment::findOrFail($id);
    }
}
