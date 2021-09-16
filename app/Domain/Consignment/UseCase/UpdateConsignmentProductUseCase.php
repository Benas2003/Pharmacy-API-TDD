<?php

namespace App\Domain\Consignment\UseCase;

use App\Domain\Consignment\DTO\UpdateConsignmentProductUseCaseDTO\UpdateConsignmentProductInput;
use App\Domain\Consignment\DTO\UpdateConsignmentProductUseCaseDTO\UpdateConsignmentProductOutput;
use App\Domain\Consignment\Exceptions\InvalidStatusException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UpdateConsignmentProductUseCase
{
    public function execute(UpdateConsignmentProductInput $updateConsignmentProductInput): UpdateConsignmentProductOutput
    {
        $product = $updateConsignmentProductInput->getProduct();
        $consignment = $updateConsignmentProductInput->getConsignment();

        if($consignment->status !== 'Created') {
            throw new InvalidStatusException(ResponseAlias::HTTP_BAD_REQUEST);
        }

        $product->update(['amount' => $updateConsignmentProductInput->getAmount()]);
        return new UpdateConsignmentProductOutput($product);
    }
}
