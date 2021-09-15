<?php

namespace App\Domain\Consignment\UseCase;

use App\Domain\Consignment\DTO\CreateConsignmentUseCaseDTO\CreateConsignmentInput;
use App\Domain\Consignment\DTO\CreateConsignmentUseCaseDTO\CreateConsignmentOutput;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Illuminate\Support\Collection;


class CreateConsignmentUseCase
{
    public function execute(CreateConsignmentInput $createConsignmentInput): CreateConsignmentOutput
    {

        $user = $createConsignmentInput->getAuth();

        $consignment = Consignment::create([
            'department_id'=>$user->id,
        ]);

        $this->addNewProductToConsignment($createConsignmentInput->getProducts(), $consignment);
        $data = [
            "Consignment Info"=>$consignment,
            "Consignment Products:"=>ConsignmentProduct::where('consignment_id', $consignment->id)->get(),
        ];

        return new CreateConsignmentOutput($consignment);
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
