<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\Product;
use Database\Factories\ProductFactory;
use Faker\Factory;
use Illuminate\Http\Response;
use Tests\TestCase;


class ProductControllerTest extends TestCase
{

    public function test_can_create_a_new_product() :void
    {

        $faker = Factory::create();

        $data = [
            'VSSLPR'=> $faker->numerify('VSSLPR#####'),
            'name'=> $faker->word,
            'storage_amount'=> $faker->numberBetween($min = 1000, $max = 9000),
            'price'=> $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
        ];

        $this->post(route('products.store'), $data)
            ->assertStatus(201)
            ->assertJson($data);
    }

    public function test_can_delete_a_product() :void
    {

        $product = Product::factory()->create();

        $this->delete(route('products.destroy', $product->id))
            ->assertStatus(204);

    }

    public function test_can_update_a_product() :void
    {

        $product = Product::factory()->create();
        $faker = Factory::create();

        $data = [
            'VSSLPR'=> $faker->numerify('VSSLPR#####'),
            'name'=> $faker->word,
            'amount'=> 0,
            'storage_amount'=> $faker->numberBetween($min = 1000, $max = 9000),
            'price'=> $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
            'status'=> 'Inactive',
        ];

        $this->put(route('products.update', $product->id), $data)
            ->assertStatus(200)
            ->assertJson($data);
    }

    public function test_can_show_a_product() {

        $product = Product::factory()->create();

        $this->get(route('products.show', $product->id))
            ->assertStatus(200);
    }

    public function testIndexReturnsDataInValidFormat() {

        $this->get(route('products.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                        '*' => [
                            'VSSLPR',
                            'name',
                            'amount',
                            'price',
                            'status',

                        ]
                ]
            );
    }
}
