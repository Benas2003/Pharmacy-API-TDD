<?php

namespace App\Domain\Consignment\UseCase;

use App\Domain\Consignment\DTO\CreateConsignmentUseCaseDTO\CreateConsignmentInput;
use App\Domain\Consignment\DTO\CreateConsignmentUseCaseDTO\CreateConsignmentOutput;
use App\Domain\Consignment\Repository\ConsignmentRepository;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Illuminate\Support\Collection;


class CreateConsignmentUseCase
{
    public function execute(CreateConsignmentInput $createConsignmentInput): CreateConsignmentOutput
    {
        $consignment = Consignment::create([
            'department_id' => $createConsignmentInput->getUserId(),
        ]);

        $this->addNewProductToConsignment($createConsignmentInput->getProducts(), $consignment);

        return new CreateConsignmentOutput($consignment, new ConsignmentRepository());
    }

    private function addNewProductToConsignment(Collection $products, Consignment $consignment): void
    {
        foreach ($products as $product) {

            ConsignmentProduct::create([
                'consignment_id' => $consignment->id,
                'product_id' => $product->id,
                'VSSLPR' => $product->VSSLPR,
                'name' => $product->name,
                'amount' => $product->amount,
                'price' => $product->amount * $product->price,
            ]);

        }
    }
}
