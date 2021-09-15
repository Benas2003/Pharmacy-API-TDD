<?php

namespace App\Http\Controllers;

use App\Domain\Consignment\DTO\UpdateConsignmentProductUseCaseDTO\UpdateConsignmentProductInput;
use App\Domain\Consignment\UseCase\UpdateConsignmentProductUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsignmentProductsController extends Controller
{
    private UpdateConsignmentProductUseCase $updateConsignmentProductUseCase;

    public function __construct(UpdateConsignmentProductUseCase $updateConsignmentProductUseCase)
    {
        $this->updateConsignmentProductUseCase = $updateConsignmentProductUseCase;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $updateConsignmentProductInput = new UpdateConsignmentProductInput($request, $id);
        return new JsonResponse($this->updateConsignmentProductUseCase->execute($updateConsignmentProductInput)->toArray());
    }
}
