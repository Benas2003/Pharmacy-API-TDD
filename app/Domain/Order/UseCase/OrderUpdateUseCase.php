<?php

namespace App\Domain\Order\UseCase;

use App\Domain\Order\DTO\OrderUpdateInput;
use App\Domain\Order\DTO\OrderUpdateOutput;
use App\Domain\Order\Exceptions\DeliveredOrderStatusException;
use App\Domain\Order\Validator\OrderValidator;
use App\Models\Order;

class OrderUpdateUseCase
{
    public function execute(OrderUpdateInput $input): OrderUpdateOutput|DeliveredOrderStatusException
    {
        $orderValidator = new OrderValidator();

        $order = Order::findOrFail($input->getId());

        $orderValidator->validateAmount($input->getAmount());

        if ($order->status !== 'Ordered') {
            throw new DeliveredOrderStatusException();
        }

        $order->update(['amount' => $input->getAmount()]);
        return new OrderUpdateOutput($order);

    }
}
