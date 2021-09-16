<?php

namespace App\Domain\Order\DTO;

use App\Models\Order;

class OrderUpdateOutput
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    public function toArray(): array
    {
        return $this->order->toArray();
    }
}
