<?php

namespace App\Http\Controllers;

use App\Domain\Consignment\UseCase\UpdateConsignmentProductUseCase;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
