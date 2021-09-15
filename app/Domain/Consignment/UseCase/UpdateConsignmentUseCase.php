<?php

namespace App\Domain\Consignment\UseCase;

use App\Domain\Consignment\DTO\InvoiceGeneratorDTO\GenerateInvoiceInput;
use App\Domain\Consignment\DTO\UpdateConsignmentUseCaseDTO\UpdateConsignmentInput;
use App\Domain\Consignment\DTO\UpdateConsignmentUseCaseDTO\UpdateConsignmentOutput;
use App\Domain\Consignment\Exceptions\InvalidNotEnoughAmountException;
use App\Domain\Consignment\Repository\ConsignmentProductRepository;
use App\Domain\Consignment\Services\generateInvoiceService;
use App\Domain\Product\Repository\ProductRepository;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UpdateConsignmentUseCase
{
    private ProductRepository $productRepository;
    private ConsignmentProductRepository $consignmentProductRepository;
    private GenerateInvoiceService $generateInvoiceService;


    public function __construct(ProductRepository $productRepository, ConsignmentProductRepository $consignmentProductRepository, GenerateInvoiceService $generateInvoiceService)
    {
        $this->productRepository = $productRepository;
        $this->consignmentProductRepository = $consignmentProductRepository;
        $this->generateInvoiceService = $generateInvoiceService;
    }

    /**
     * @throws Exception
     */
    public function execute(UpdateConsignmentInput $updateConsignmentInput): UpdateConsignmentOutput
    {

        $consignment = $updateConsignmentInput->getConsignment();

        if($updateConsignmentInput->getStatus() === 'Processed')
        {
            $consignment_products = $this->getConsignmentProducts($consignment->id);

            foreach ($consignment_products as $consignment_product)
            {
                $storage_product = $this->productRepository->getProductById($consignment_product->product_id);
                $this->checkHowMuchAmountCanBeGivenAway($storage_product, $consignment_product);
            }

            $this->InvoiceService($consignment, $consignment_products, $updateConsignmentInput);
        }

        $consignment->update([
            'status'=>$updateConsignmentInput->getStatus(),
        ]);

        return new UpdateConsignmentOutput($consignment);
    }

    private function checkHowMuchAmountCanBeGivenAway(Product $storage_product, ConsignmentProduct $consignment_product): void
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

    private function getConsignmentProducts(int $id): Collection
    {
        $consignment_products = $this->consignmentProductRepository->getAllProductsByConsignmentId($id);
        $this->checkIfAmountIsEnough($consignment_products);
        return $consignment_products;
    }

    private function checkIfAmountIsEnough(Collection $consignment_products): void
    {
        foreach ($consignment_products as $consignment_product) {
            $storage_product = $this->productRepository->getProductById($consignment_product->product_id);

            if ($storage_product->amount === 0) {
                throw new InvalidNotEnoughAmountException(ResponseAlias::HTTP_METHOD_NOT_ALLOWED);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function InvoiceService(Consignment $consignment, Collection $consignment_products, UpdateConsignmentInput $updateConsignmentInput): void
    {
        $generateInvoiceInput = new GenerateInvoiceInput($consignment, $consignment_products, $updateConsignmentInput->getAuth());
        $invoice = $this->generateInvoiceService->InvoiceGenerator($generateInvoiceInput);
        $invoice->download();
    }
}
