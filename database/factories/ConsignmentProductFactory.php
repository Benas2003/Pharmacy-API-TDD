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
    public function definition(): array
    {
        $productFactory = new \App\Domain\Product\Factory\ProductFactory();
        $product = Product::create($productFactory->fakeProduct());

        $consignmentProductFactory = new \App\Domain\Consignment\Factory\ConsignmentProductFactory();
        return $consignmentProductFactory->fakeConsignmentProduct($product);
    }
}
