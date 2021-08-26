<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'VSSLPR'=> $this->faker->numerify('VSSLPR#####'),
            'name'=> $this->faker->word,
            'amount'=>$this->faker->numberBetween($min = 1, $max = 50000),
            'storage_amount'=> $this->faker->numberBetween($min = 1000, $max = 9000),
            'price'=> $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
        ];
    }
}
