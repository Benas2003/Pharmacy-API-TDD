<?php

namespace App\Domain\Consignment\DTO\UpdateConsignmentProductUseCaseDTO;

use App\Models\ConsignmentProduct;

class UpdateConsignmentProductOutput
{
    private ConsignmentProduct $consignmentProduct;

    public function __construct(ConsignmentProduct $consignmentProduct)
    {
        $this->consignmentProduct = $consignmentProduct;
    }

    /**
     * @return ConsignmentProduct
     */
    public function getConsignmentProduct(): ConsignmentProduct
    {
        return $this->consignmentProduct;
    }

    public function toArray(): array
    {
        return $this->consignmentProduct->toArray();
    }
}
