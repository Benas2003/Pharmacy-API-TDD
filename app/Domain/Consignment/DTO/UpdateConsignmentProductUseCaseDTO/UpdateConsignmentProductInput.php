<?php

namespace App\Domain\Consignment\DTO\UpdateConsignmentProductUseCaseDTO;

use App\Domain\Consignment\Repository\ConsignmentProductRepository;
use App\Domain\Consignment\Repository\ConsignmentRepository;
use App\Domain\Consignment\Validator\ConsignmentValidator;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Illuminate\Http\Request;

class UpdateConsignmentProductInput
{
    private ConsignmentProduct $product;
    private Consignment $consignment;
    private int $amount;

    public function __construct(
        Request $request,
        int $id,
        ConsignmentValidator $consignmentValidator,
        ConsignmentProductRepository $consignmentProductRepository,
        ConsignmentRepository $consignmentRepository
    )
    {
        $consignmentValidator->validateConsignmentProductAmount($request);

        $this->product = $consignmentProductRepository->getConsignmentProductById($id);
        $this->consignment = $consignmentRepository->getSpecificConsignment($this->product->consignment_id);
        $this->amount = $request->amount;
    }

    /**
     * @return ConsignmentProduct
     */
    public function getProduct(): ConsignmentProduct
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return Consignment
     */
    public function getConsignment(): Consignment
    {
        return $this->consignment;
    }
}
