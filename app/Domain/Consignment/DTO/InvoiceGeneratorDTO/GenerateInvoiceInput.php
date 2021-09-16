<?php

namespace App\Domain\Consignment\DTO\InvoiceGeneratorDTO;

use App\Models\Consignment;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class GenerateInvoiceInput
{
    private Collection $consignmentProducts;
    private Consignment $consignment;
    private string $userName;

    public function __construct(Consignment $consignment, Collection $consignmentProducts, string $userName)
    {
        $this->consignment = $consignment;
        $this->consignmentProducts = $consignmentProducts;
        $this->userName = $userName;
    }

    /**
     * @return Collection
     */
    public function getConsignmentProducts(): Collection
    {
        return $this->consignmentProducts;
    }

    /**
     * @return Consignment
     */
    public function getConsignment(): Consignment
    {
        return $this->consignment;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }
}
