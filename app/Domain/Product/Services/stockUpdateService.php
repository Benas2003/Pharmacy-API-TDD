<?php

namespace App\Domain\Product\Services;

use App\Models\Order;
use App\Models\Product;

class stockUpdateService
{
    public function execute(int $id, int $amount): void
    {
        $order = Order::findOrFail($id);
        $product = Product::findOrFail($order->product_id);

        $product->update([
            'amount'=>$product->amount+$amount,
        ]);

        $order->update([
            'price' => $product->price * $amount,
            'amount'=> $amount,
            'status'=>'Delivered',
        ]);
    }
}
