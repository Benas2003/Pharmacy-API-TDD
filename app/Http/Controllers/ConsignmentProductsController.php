<?php

namespace App\Http\Controllers;

use App\Domain\Consignment\UseCase\UpdateConsignmentProductUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsignmentProductsController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $updateConsignmentProductUseCase = new UpdateConsignmentProductUseCase($request);
        return $updateConsignmentProductUseCase->execute($id);
    }
}
