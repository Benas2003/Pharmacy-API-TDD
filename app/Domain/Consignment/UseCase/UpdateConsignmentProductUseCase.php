<?php

namespace App\Domain\Consignment\UseCase;

use App\Domain\Consignment\Exceptions\InvalidStatusException;
use App\Domain\Consignment\Validator\ConsignmentValidator;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UpdateConsignmentProductUseCase
{

    private Request $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(int $id): JsonResponse
    {

        $consignmentValidator = new ConsignmentValidator();
        $consignmentValidator->validateAmount($this->request);


        $product = ConsignmentProduct::findOrFail($id);
        $consignment = Consignment::find($product->consignment_id);

        if($consignment->status === 'Created')
        {
            $product->update(['amount'=>$this->request['amount']]);
            return new JsonResponse($product);
        }

        throw new InvalidStatusException(ResponseAlias::HTTP_METHOD_NOT_ALLOWED);
    }
}
