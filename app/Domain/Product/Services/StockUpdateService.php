<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\DTO\StockUpdateServiceDTO\StockUpdateInput;
use App\Models\Order;
use App\Models\Product;

class StockUpdateService
{
    public function execute(StockUpdateInput $stockUpdateInput): void
    {
        $order = Order::findOrFail($stockUpdateInput->getId());
        $product = Product::findOrFail($order->product_id);

        $product->update([
            'amount'=>$product->amount+$stockUpdateInput->getAmount(),
        ]);

        $order->update([
            'price' => $product->price * $stockUpdateInput->getAmount(),
            'amount'=> $stockUpdateInput->getAmount(),
            'status'=>'Delivered',
        ]);
    }
}
