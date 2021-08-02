<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\Product;
use Faker\Factory;
use Tests\TestCase;


class ProductControllerTest extends TestCase
{

    public function test_can_create_a_new_product() :void
    {

        $faker = Factory::create();

        $data = [
            'VSSLPR'=> $VSSLPR = $faker->numerify('VSSLPR#####'),
            'name'=> $name = $faker->word,
            'storage_amount'=> $st_am = $faker->numberBetween($min = 1000, $max = 9000),
            'price'=> $price = $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
        ];

        $this->json('POST', '/api/products/', [
            'VSSLPR'=> $VSSLPR,
            'name'=> $name,
            'storage_amount'=> $st_am,
            'price'=> $price,
        ])
            ->assertStatus(201)
            ->assertJson($data);
    }

    public function test_can_delete_post() {

        $product = Product::factory()->make();

        $this->json('POST', '/api/products/{id}', ['id'=>$product->id])->assertStatus(204);

    }
}
