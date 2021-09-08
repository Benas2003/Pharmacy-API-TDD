<?php

namespace App\Domain\Consignment\UseCase;

use App\Domain\Consignment\Exceptions\InvalidConsignmentStatusException;
use App\Models\Consignment;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DestroyConsignmentUseCase
{
    public function execute(int $id): JsonResponse
    {
        $consignment = Consignment::findOrFail($id);

        if($consignment->status !== 'Created')
        {
            throw new InvalidConsignmentStatusException(ResponseAlias::HTTP_METHOD_NOT_ALLOWED);
        }
        $consignment->delete();
        return new JsonResponse(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
