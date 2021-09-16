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

        $this->updateAmountIfConsignmentProcessed($updateConsignmentInput, $consignment);

        $consignment->update([
            'status'=>$updateConsignmentInput->getStatus(),
        ]);

        return new UpdateConsignmentOutput($consignment);
    }

    /**
     * @throws Exception
     */
    private function updateAmountIfConsignmentProcessed(UpdateConsignmentInput $updateConsignmentInput, Consignment $consignment): void
    {
        if ($updateConsignmentInput->getStatus() === 'Processed') {
            $consignmentProducts = $this->getConsignmentProducts($consignment->id);

            foreach ($consignmentProducts as $consignmentProduct) {
                $storage_product = $this->productRepository->getProductById($consignmentProduct->product_id);
                $this->checkHowMuchAmountCanBeGivenAway($storage_product, $consignmentProduct);
            }

            $this->InvoiceService($consignment, $consignmentProducts, $updateConsignmentInput);
        }
    }

    private function checkHowMuchAmountCanBeGivenAway(Product $storageProduct, ConsignmentProduct $consignmentProduct): void
    {
        if ($storageProduct->amount >= $consignmentProduct->amount) {
            $storageProduct->update([
                'amount' => $storageProduct->amount - $consignmentProduct->amount,
            ]);
        } else {
            $consignmentProduct->update([
                'amount' => $storageProduct->amount
            ]);
            $storageProduct->update([
                'amount' => 0
            ]);
        }
    }

    private function getConsignmentProducts(int $id): Collection
    {
        $consignmentProducts = $this->consignmentProductRepository->getAllProductsByConsignmentId($id);
        $badAmount =  $this->checkIfAmountIsEnough($consignmentProducts);
        if($badAmount === false)
        {
            throw new InvalidNotEnoughAmountException(ResponseAlias::HTTP_BAD_REQUEST);
        }
        return $consignmentProducts;
    }

    private function checkIfAmountIsEnough(Collection $consignmentProducts): bool
    {
        foreach ($consignmentProducts as $consignmentProduct) {
            $storageProduct = $this->productRepository->getProductById($consignmentProduct->product_id);

            if ($storageProduct->amount === 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws Exception
     */
    private function InvoiceService(Consignment $consignment, Collection $consignmentProducts, UpdateConsignmentInput $updateConsignmentInput): void
    {
        $generateInvoiceInput = new GenerateInvoiceInput($consignment, $consignmentProducts, $updateConsignmentInput->getUserName());
        $invoice = $this->generateInvoiceService->invoiceGenerator($generateInvoiceInput);
        $invoice->download();
    }
}
