<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\Product;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;


class ProductControllerTest extends TestCase
{
//    use RefreshDatabase;
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
            ->assertStatus(ResponseAlias::HTTP_CREATED)
            ->assertJson($data);
    }

    public function test_can_delete_a_product() :void
    {

        $product = Product::factory()->create();

        $this->delete(route('products.destroy', $product->id))
            ->assertStatus(ResponseAlias::HTTP_NO_CONTENT);

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
            ->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJson($data);
    }

    public function test_can_show_a_product() {

        $product = Product::factory()->create();

        $this->get(route('products.show', $product->id))
            ->assertStatus(ResponseAlias::HTTP_OK);
    }

    public function testIndexReturnsDataInValidFormat() {

        $this->get(route('products.index'))
            ->assertStatus(ResponseAlias::HTTP_OK)
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
    public function testSearchReturnsDataInValidFormat() {

        $this->get(route('products.search', 'epe'))
            ->assertStatus(ResponseAlias::HTTP_OK)
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
