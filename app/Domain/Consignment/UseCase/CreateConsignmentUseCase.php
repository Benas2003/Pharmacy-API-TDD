<?php

namespace App\Domain\Consignment\UseCase;

use App\Domain\Consignment\Exceptions\InvalidProductInformationInputException;
use App\Domain\Consignment\Validator\ConsignmentValidator;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CreateConsignmentUseCase
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function execute(): JsonResponse
    {
        $requested_products = $this->request->toArray();

        $consignment = Consignment::create([
            'department_id'=>Auth::user()->id,

        ]);

        $this->addNewProductToConsignment($requested_products, $consignment);
        $data = [
            "Consignment Info"=>$consignment,
            "Consignment Products:"=>ConsignmentProduct::where('consignment_id', $consignment->id)->get(),
        ];

        return new JsonResponse($data, ResponseAlias::HTTP_CREATED);
    }

    private function addNewProductToConsignment(array $requested_products, Consignment $consignment): void
    {
        $consignmentValidator = new ConsignmentValidator();


        foreach ($requested_products as $requested_product) {
            if (!$consignmentValidator->validateAmount($requested_product) || !$consignmentValidator->validateId($requested_product)) {
                throw new InvalidProductInformationInputException();
            }

            $existing_product = Product::findOrFail($requested_product['id']);
            ConsignmentProduct::create([
                'consignment_id' => $consignment->id,
                'product_id' => $existing_product->id,
                'VSSLPR' => $existing_product->VSSLPR,
                'name' => $existing_product->name,
                'amount' => $requested_product['amount'],
                'price' => $requested_product['amount'] * $existing_product->price,
            ]);

        }
    }
}
