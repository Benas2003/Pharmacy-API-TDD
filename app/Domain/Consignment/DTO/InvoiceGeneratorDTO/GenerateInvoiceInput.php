<?php

namespace App\Domain\Consignment\DTO\InvoiceGeneratorDTO;

use App\Models\Consignment;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class GenerateInvoiceInput
{
    private Collection $consignment_products;
    private Consignment $consignment;
    private Authenticatable $auth;

    public function __construct(Consignment $consignment, Collection $consignment_products, Authenticatable $auth)
    {
        $this->consignment = $consignment;
        $this->consignment_products = $consignment_products;
        $this->auth = $auth;
    }

    /**
     * @return Collection
     */
    public function getConsignmentProducts(): Collection
    {
        return $this->consignment_products;
    }

    /**
     * @return Consignment
     */
    public function getConsignment(): Consignment
    {
        return $this->consignment;
    }

    /**
     * @return Authenticatable
     */
    public function getAuth(): Authenticatable
    {
        return $this->auth;
    }


}
