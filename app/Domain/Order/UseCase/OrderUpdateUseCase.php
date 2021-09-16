<?php

namespace App\Domain\Order\UseCase;

use App\Domain\Order\DTO\OrderUpdateInput;
use App\Domain\Order\DTO\OrderUpdateOutput;
use App\Domain\Order\Exceptions\DeliveredOrderStatusException;
use App\Domain\Order\Validator\OrderValidator;
use App\Models\Order;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderUpdateUseCase
{
    private OrderValidator $orderValidator;

    public function __construct(OrderValidator $orderValidator)
    {
        $this->orderValidator = $orderValidator;
    }

    public function execute(OrderUpdateInput $input): OrderUpdateOutput|DeliveredOrderStatusException
    {
        $order = Order::findOrFail($input->getId());
        $this->orderValidator->validateAmount($input->getAmount());

        if ($order->status !== 'Ordered') {
            throw new DeliveredOrderStatusException(ResponseAlias::HTTP_BAD_REQUEST);
        }

        $order->update(['amount' => $input->getAmount()]);
        return new OrderUpdateOutput($order);
    }
}
