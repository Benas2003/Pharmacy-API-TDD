<?php

namespace App\Domain\Product\Factory;

use Faker\Generator;

class ProductFactory
{
    private Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function fakeProduct(): array
    {
        $product = [];
        $product['VSSLPR'] = $this->generator->numerify('VSSLPR#####');
        $product['name'] = $this->generator->word;
        $product['amount'] = $this->generator->numberBetween($min = 1, $max = 10000);
        $product['storage_amount'] = $this->generator->numberBetween($min = 1000, $max = 20000);
        $product['price'] = $this->generator->randomFloat($nbMaxDecimals = NULL, $min = 0.0001, $max = 5.00);

        return $product;
    }
}
