<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $productFactory = new \App\Domain\Product\Factory\ProductFactory();
        $product = Product::create($productFactory->fakeProduct());

        $orderFactory = new \App\Domain\Order\Factory\OrderFactory(\Faker\Factory::create());
        return $orderFactory->fakeOrder($product);
    }
}
