<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\Order;
use App\Models\Product;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;


class ProductControllerTest extends TestCase
{
    public const ADMIN_EMAIL = 'Admin@icloud.com';
    public const ADMIN_PASSWORD = 'Admin';

    public const PHARMACIST_EMAIL = 'jonas.jonaitis@icloud.com';
    public const PHARMACIST_PASSWORD = 'JJKK';

    public const DEPARTMENT_EMAIL = 'cr@icloud.com';
    public const DEPARTMENT_PASSWORD = 'CR91';

    public function test_can_create_a_new_product_with_Admin_role(): void
    {
        $faker = Factory::create();

        $adminLoginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $adminLoginResponse->json('token');

        $data = [
            'VSSLPR' => $faker->numerify('VSSLPR#####'),
            'name' => $faker->word,
            'storage_amount' => $faker->numberBetween($min = 1000, $max = 9000),
            'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0.001, $max = 1000.00),
        ];

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('products.store'), $data)
            ->assertStatus(ResponseAlias::HTTP_CREATED)
            ->assertJson($data);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_cannot_create_a_new_product_with_Pharmacist_role(): void
    {
        $faker = Factory::create();

        $pharmacistLoginResponse = $this->post(route('login'), [
            'email' => self::PHARMACIST_EMAIL,
            'password' => self::PHARMACIST_PASSWORD,
            'password_confirmation' => self::PHARMACIST_PASSWORD,
        ]);
        $pharmacistToken = $pharmacistLoginResponse->json('token');

        $data = [
            'VSSLPR' => $faker->numerify('VSSLPR#####'),
            'name' => $faker->word,
            'storage_amount' => $faker->numberBetween($min = 1000, $max = 9000),
            'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0.001, $max = 1000.00),
        ];

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('products.store'), $data)
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_can_delete_a_product_with_admin_role(): void
    {
        $adminLoginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $adminLoginResponse->json('token');

        $product = Product::factory()->create();

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->delete(route('products.destroy', $product->id))
            ->assertStatus(ResponseAlias::HTTP_NO_CONTENT);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_cannot_delete_a_product_with_pharmacist_role(): void
    {
        $pharmacistLoginResponse = $this->post(route('login'), [
            'email' => self::PHARMACIST_EMAIL,
            'password' => self::PHARMACIST_PASSWORD,
            'password_confirmation' => self::PHARMACIST_PASSWORD,
        ]);
        $pharmacistToken = $pharmacistLoginResponse->json('token');

        $product = Product::factory()->create();

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->delete(route('products.destroy', $product->id))
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_can_update_a_product_with_admin_role(): void
    {
        $faker = Factory::create();

        $adminLoginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $adminLoginResponse->json('token');

        $product = Product::factory()->create();

        $data = [
            'VSSLPR' => $faker->numerify('VSSLPR#####'),
            'name' => $faker->word,
            'storage_amount' => $faker->numberBetween($min = 1000, $max = 3000),
            'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0.001, $max = 10.00),
        ];

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->put(route('products.update', $product->id), $data)
            ->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJson($data);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_can_not_update_a_product_with_pharmacist_role(): void
    {
        $faker = Factory::create();

        $pharmacistLoginResponse = $this->post(route('login'), [
            'email' => self::PHARMACIST_EMAIL,
            'password' => self::PHARMACIST_PASSWORD,
            'password_confirmation' => self::PHARMACIST_PASSWORD,
        ]);
        $pharmacistToken = $pharmacistLoginResponse->json('token');

        $product = Product::factory()->create();

        $data = [
            'VSSLPR' => $faker->numerify('VSSLPR#####'),
            'name' => $faker->word,
            'amount' => 0,
            'storage_amount' => $faker->numberBetween($min = 1000, $max = 9000),
            'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0.001, $max = 1000.00),
        ];

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->put(route('products.update', $product->id), $data)
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_can_show_a_product_with_department_role(): void
    {
        $departmentLoginResponse = $this->post(route('login'), [
            'email' => self::DEPARTMENT_EMAIL,
            'password' => self::DEPARTMENT_PASSWORD,
            'password_confirmation' => self::DEPARTMENT_PASSWORD,
        ]);
        $departmentToken = $departmentLoginResponse->json('token');

        $product = Product::factory()->create();

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->get(route('products.show', $product->id))
            ->assertStatus(ResponseAlias::HTTP_OK);

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_can_update_product_amount_with_Pharmacist_role(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::PHARMACIST_EMAIL,
            'password' => self::PHARMACIST_PASSWORD,
            'password_confirmation' => self::PHARMACIST_PASSWORD,
        ]);
        $pharmacistToken = $loginResponse->json('token');

        $order = Order::factory()->create();

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->put(route('stock.update', [$order->id, 100]))
            ->assertStatus(ResponseAlias::HTTP_OK);

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }
}
