<?php

namespace App\Domain\Consignment\UseCase;

use App\Domain\Consignment\Exceptions\InvalidNotEnoughAmountException;
use App\Domain\Consignment\Services\generateInvoiceService;
use App\Domain\Consignment\Validator\ConsignmentValidator;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UpdateConsignmentUseCase
{
    private Request $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @throws Exception
     */
    public function execute($id): JsonResponse
    {
        $consignmentValidator = new ConsignmentValidator();

        $consignmentValidator->validateStatus($this->request);


        $consignment = Consignment::findOrFail($id);

        if($this->request['status'] === 'Processed')
        {
            $consignment_products = $this->getConsignmentProducts($id);

            foreach ($consignment_products as $consignment_product)
            {
                $storage_product = Product::findOrFail($consignment_product->product_id);
                $this->checkHowMuchAmountCanBeGivenAway($storage_product, $consignment_product);
            }

            $this->InvoiceService($consignment, $consignment_products);
        }

        $consignment->update([
            'status'=>$this->request['status'],
        ]);

        return new JsonResponse($consignment);
    }

    private function checkIfAmountIsEnough($consignment_products): void
    {
        foreach ($consignment_products as $consignment_product) {
            $storage_product = Product::findOrFail($consignment_product->product_id);

            if ($storage_product->amount === 0) {
                throw new InvalidNotEnoughAmountException(ResponseAlias::HTTP_METHOD_NOT_ALLOWED);
            }
        }
    }

    private function checkHowMuchAmountCanBeGivenAway($storage_product, mixed $consignment_product): void
    {
        if ($storage_product->amount >= $consignment_product->amount) {
            $storage_product->update([
                'amount' => $storage_product->amount - $consignment_product->amount,
            ]);
        } else {
            $consignment_product->update([
                'amount' => $storage_product->amount
            ]);
            $storage_product->update([
                'amount' => 0
            ]);
        }
    }

    private function getConsignmentProducts($id)
    {
        $consignment_products = ConsignmentProduct::where('consignment_id', $id)->get();
        $this->checkIfAmountIsEnough($consignment_products);
        return $consignment_products;
    }

    /**
     * @throws Exception
     */
    private function InvoiceService($consignment, $consignment_products): void
    {
        $generateInvoiceService = new generateInvoiceService();

        $invoice = $generateInvoiceService->generateInvoice($consignment, $consignment_products);
        $invoice->download();
    }
}
