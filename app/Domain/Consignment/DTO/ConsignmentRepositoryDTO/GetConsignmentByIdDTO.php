<?php

namespace App\Domain\Consignment\DTO\ConsignmentRepositoryDTO;

use Illuminate\Support\Collection;

class GetConsignmentByIdDTO
{
    private string $status;
    private Collection $products;

    public function __construct(string $status, Collection $products)
    {
        $this->status = $status;
        $this->products = $products;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
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
        return array('Status'=>$this->status, 'Products'=>$this->products);
    }
}
