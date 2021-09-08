<?php

namespace App\Http\Controllers;

use App\Domain\Order\UseCase\OrderUpdateUseCase;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param int $amount
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id, int $amount): JsonResponse
    {
        $orderUpdateUseCase = new OrderUpdateUseCase();
        $orderUpdateUseCase->execute($id, $amount);
        return $orderUpdateUseCase->execute($id, $amount);
    }
}
