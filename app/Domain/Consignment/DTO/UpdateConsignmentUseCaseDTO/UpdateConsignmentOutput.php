<?php

namespace App\Domain\Consignment\DTO\UpdateConsignmentUseCaseDTO;

use App\Models\Consignment;

class UpdateConsignmentOutput
{
    private Consignment $consignment;

    public function __construct(Consignment $consignment)
    {
        $this->consignment = $consignment;
    }

    /**
     * @return Consignment
     */
    public function getConsignment(): Consignment
    {
        return $this->consignment;
    }

    public function toArray(): array
    {
        return $this->consignment->toArray();
    }
}
