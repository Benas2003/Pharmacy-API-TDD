<?php

namespace App\Domain\Order\Factory;

use App\Models\Product;
use Faker\Factory;

class OrderFactory
{
    public function fakeOrder(Product $product): array
    {
        $faker = Factory::create();

        $ordering_amount = $product->storage_amount-$product->amount;

        $order = [];
        $order['product_id'] = $product->id;
        $order['EUR_INT_O'] = $faker->uuid;
        $order['name'] = $product->name;
        $order['amount'] = $ordering_amount;
        $order['price'] = $product->price*$ordering_amount;

        return $order;
    }
}
