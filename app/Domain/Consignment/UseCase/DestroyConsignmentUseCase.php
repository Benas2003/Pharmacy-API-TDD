<?php

namespace App\Domain\Consignment\UseCase;

use App\Domain\Consignment\DTO\DestroyConsignmentUseCaseDTO\DestroyConsignmentInput;
use App\Domain\Consignment\Exceptions\InvalidConsignmentStatusException;
use App\Domain\Consignment\Repository\ConsignmentRepository;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DestroyConsignmentUseCase
{
    private ConsignmentRepository $consignmentRepository;

    public function __construct(ConsignmentRepository $consignmentRepository)
    {
        $this->consignmentRepository = $consignmentRepository;
    }

    public function execute(DestroyConsignmentInput $destroyConsignmentInput): void
    {
        $consignment = $this->consignmentRepository->getSpecificConsignment($destroyConsignmentInput->getId());

        if($consignment->status !== 'Created') {
            throw new InvalidConsignmentStatusException(ResponseAlias::HTTP_BAD_REQUEST);
        }
        $consignment->delete();
    }
}
