<?php

namespace App\Domain\Consignment\DTO\CreateConsignmentUseCaseDTO;

use App\Domain\Consignment\Repository\ConsignmentRepository;
use App\Models\Consignment;
use Illuminate\Support\Collection;

class CreateConsignmentOutput
{
    private Consignment $consignment;
    private Collection $products;

    public function __construct(Consignment $consignment)
    {
        $consignmentRepository = new ConsignmentRepository();
        $this->consignment = $consignment;
        $this->products = $consignmentRepository->getConsignmentById($consignment->id)->getProducts();
    }

    /**
     * @return Consignment
     */
    public function getConsignment(): Consignment
    {
        return $this->consignment;
    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function toArray(): array
    {
        return array('Consignment'=>$this->consignment->toArray(), 'Consignment Products'=>$this->products->toArray());
    }
}
