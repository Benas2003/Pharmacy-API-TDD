<?php

namespace App\Http\Controllers;

use App\Domain\Order\DTO\OrderUpdateInput;
use App\Domain\Order\UseCase\OrderUpdateUseCase;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    private OrderUpdateUseCase $orderUpdateUseCase;

    public function __construct(OrderUpdateUseCase $orderUpdateUseCase)
    {
        $this->orderUpdateUseCase = $orderUpdateUseCase;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $amount
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id, int $amount): JsonResponse
    {
        $orderUpdateInput = new OrderUpdateInput($id, $amount);
        return new JsonResponse($this->orderUpdateUseCase->execute($orderUpdateInput)->toArray());
    }
}
