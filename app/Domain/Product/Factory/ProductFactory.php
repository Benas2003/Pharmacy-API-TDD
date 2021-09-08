<?php

namespace App\Domain\Product\Factory;

use Faker\Factory;

class ProductFactory
{
    public function fakeProduct(): array
    {
        $faker = Factory::create();

        $product = [];

        $product['VSSLPR'] = $faker->numerify('VSSLPR#####');
        $product['name'] = $faker->word;
        $product['amount'] = $faker->numberBetween($min = 1, $max = 10000);
        $product['storage_amount'] = $faker->numberBetween($min = 1000, $max = 20000);
        $product['price'] = $faker->randomFloat($nbMaxDecimals = NULL, $min = 0.0001, $max = 5.00);

        return $product;
    }
}
