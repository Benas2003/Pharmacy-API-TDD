<?php

namespace App\Domain\Order\Factory;

use App\Models\Product;
use Faker\Generator;

class OrderFactory
{
    private Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function fakeOrder(Product $product): array
    {
        $ordering_amount = $product->storage_amount-$product->amount;

        $order = [];
        $order['product_id'] = $product->id;
        $order['EUR_INT_O'] = $this->generator->uuid;
        $order['name'] = $product->name;
        $order['amount'] = $ordering_amount;
        $order['price'] = $product->price*$ordering_amount;

        return $order;
    }
}
