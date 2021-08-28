<?php

namespace Database\Factories;

use App\Models\ConsignmentProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsignmentProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConsignmentProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::factory()->create();
        return [
            'VSSLPR'=>$product->VSSLPR,
            'name'=>$product->name,
            'amount'=> $amount = $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0.0001, $max = 1000.00),
            'price' => $amount*$product->price,
        ];
    }
}
