<?php

namespace App\Domain\Consignment\Factory;

class ConsignmentFactory
{
    public function fakeConsignment(): array
    {
        $consignment = [];

        $consignment['status'] = array_rand(['Created', 'Processed', 'Given away']);

        return $consignment;
    }
}
