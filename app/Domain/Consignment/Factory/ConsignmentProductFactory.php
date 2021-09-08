<?php

namespace App\Domain\Consignment\Factory;

use App\Models\Product;
use Faker\Factory;

class ConsignmentProductFactory
{
    public function fakeConsignmentProduct(Product $product): array
    {
        $faker = Factory::create();

        $consignmentProduct = [];

        $consignmentProduct['VSSLPR'] = $product->VSSLPR;
        $consignmentProduct['product_id'] = $product->id;
        $consignmentProduct['name'] = $product->name;
        $consignmentProduct['amount'] = $amount = $faker->randomFloat($nbMaxDecimals = NULL, $min = 0.0001, $max = 1000.00);
        $consignmentProduct['price'] = $amount*$product->price;

        return $consignmentProduct;
    }
}
