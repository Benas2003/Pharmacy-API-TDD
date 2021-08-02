<?php

namespace Tests\Feature\Http\Controllers\API;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_a_new_product() :void
    {

        $faker = Factory::create();

        $response = $this->json('POST', '/api/products', [
            'VSSLPR'=> $faker->numerify('VSSLPR#####'),
            'name'=> $faker->word,
            'storage_amount'=> $faker->numberBetween($min = 1000, $max = 9000),
            'price'=> $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
        ]);

        $response->assertStatus(201);
    }
}
