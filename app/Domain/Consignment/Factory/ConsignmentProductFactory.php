<?php

namespace App\Domain\Consignment\Factory;

use App\Models\Product;
use Faker\Factory;
use Faker\Generator;

class ConsignmentProductFactory
{
    private Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function fakeConsignmentProduct(Product $product): array
    {
        $consignmentProduct = [];

        $consignmentProduct['VSSLPR'] = $product->VSSLPR;
        $consignmentProduct['product_id'] = $product->id;
        $consignmentProduct['name'] = $product->name;
        $consignmentProduct['amount'] = $amount = $this->generator->randomFloat($nbMaxDecimals = NULL, $min = 0.0001, $max = 1000.00);
        $consignmentProduct['price'] = $amount*$product->price;

        return $consignmentProduct;
    }
}
