<?php

namespace App\Domain\Order\Repository;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrderRepository
{
    public function getAllOrdersFromSpecificRows(): Collection
    {
        return Order::select('id', 'EUR_INT_O', 'name', 'amount', 'price', 'created_at')->where('status', 'Ordered')->where('created_at','like', date('Y-m-d').'%')->get();
    }

    public function getAllOrdersWithOrderedStatus($id): Collection
    {
        return Order::where('product_id', $id)->where('status', 'Ordered')->get();
    }
}
