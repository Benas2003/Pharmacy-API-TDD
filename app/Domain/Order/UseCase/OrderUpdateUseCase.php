<?php

namespace App\Domain\Order\UseCase;

use App\Domain\Order\Exceptions\DeliveredOrderStatusException;
use App\Domain\Order\Validator\OrderValidator;
use App\Models\Order;

use Illuminate\Http\JsonResponse;

class OrderUpdateUseCase
{
    public function execute(int $id, int $amount): JsonResponse|DeliveredOrderStatusException
    {
        $order = Order::findOrFail($id);
        $orderValidator = new OrderValidator();

        $orderValidator->validateAmount($amount);

        if ($order->status === 'Ordered') {
            $order->update(['amount' => $amount]);
            return new JsonResponse($order);
        }
        throw new DeliveredOrderStatusException();
    }
}
