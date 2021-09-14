<?php

namespace App\Domain\Order\Infrastructure\Database;

use App\Models\Order;

class OrderDatabase
{
    public function getAllOrdersFromSpecificRows()
    {
        return Order::select('id', 'EUR_INT_O', 'name', 'amount', 'price', 'created_at')->where('status', 'Ordered')->where('created_at','like', date('Y-m-d').'%')->get();
    }

    public function getAllOrdersWithOrderedStatus($id)
    {
        return Order::where('product_id', $id)->where('status', 'Ordered')->get();
    }
}
