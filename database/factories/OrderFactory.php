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
    public function definition()
    {
        $product = Product::factory()->create();
        $ordering_amount = $product->storage_amount-$product->amount;
        return [
            'product_id'=>$product->id,
            'EUR_INT_O'=>$this->faker->uuid,
            'name' => $product->name,
            'amount' => $ordering_amount,
            'price'=> $product->price*$ordering_amount,
        ];
    }
}
