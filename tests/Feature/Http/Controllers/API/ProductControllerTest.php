<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;


class ProductControllerTest extends TestCase
{
    public function test_can_create_a_new_product_with_Admin_role(): void
    {
        $faker = Factory::create();
        $adminPassword = $faker->password;

        $admin = User::factory()->create([
            'password' => bcrypt($adminPassword),
        ]);

        $admin->assignRole('Admin');

        $adminLoginResponse = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => $adminPassword,
            'password_confirmation' => $adminPassword,
        ]);
        $adminToken = $adminLoginResponse->json('token');

        $data = [
            'VSSLPR' => $faker->numerify('VSSLPR#####'),
            'name' => $faker->word,
            'storage_amount' => $faker->numberBetween($min = 1000, $max = 9000),
            'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
        ];

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('products.store'), $data)
            ->assertStatus(ResponseAlias::HTTP_CREATED)
            ->assertJson($data);
    }

    public function test_cannot_create_a_new_product_with_Pharmacist_role(): void
    {
        $faker = Factory::create();
        $adminPassword = $faker->password;

        $pharmacist = User::factory()->create([
            'password' => bcrypt($adminPassword),
        ]);

        $pharmacist->assignRole('Pharmacist');

        $pharmacistLoginResponse = $this->post(route('login'), [
            'email' => $pharmacist->email,
            'password' => $adminPassword,
            'password_confirmation' => $adminPassword,
        ]);
        $pharmacistToken = $pharmacistLoginResponse->json('token');

        $data = [
            'VSSLPR' => $faker->numerify('VSSLPR#####'),
            'name' => $faker->word,
            'storage_amount' => $faker->numberBetween($min = 1000, $max = 9000),
            'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
        ];

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('products.store'), $data)
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);
    }

    public function test_can_delete_a_product_with_admin_role(): void
    {
        $faker = Factory::create();
        $adminPassword = $faker->password;

        $admin = User::factory()->create([
            'password' => bcrypt($adminPassword),
        ]);

        $admin->assignRole('Admin');

        $adminLoginResponse = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => $adminPassword,
            'password_confirmation' => $adminPassword,
        ]);
        $adminToken = $adminLoginResponse->json('token');

        $product = Product::factory()->create();

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->delete(route('products.destroy', $product->id))
            ->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_cannot_delete_a_product_with_pharmacist_role(): void
    {
        $faker = Factory::create();
        $adminPassword = $faker->password;

        $pharmacist = User::factory()->create([
            'password' => bcrypt($adminPassword),
        ]);

        $pharmacist->assignRole('Pharmacist');

        $pharmacistLoginResponse = $this->post(route('login'), [
            'email' => $pharmacist->email,
            'password' => $adminPassword,
            'password_confirmation' => $adminPassword,
        ]);
        $pharmacistToken = $pharmacistLoginResponse->json('token');

        $product = Product::factory()->create();

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->delete(route('products.destroy', $product->id))
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

    }

    public function test_can_update_a_product_with_admin_role(): void
    {
        $faker = Factory::create();
        $adminPassword = $faker->password;

        $admin = User::factory()->create([
            'password' => bcrypt($adminPassword),
        ]);

        $admin->assignRole('Admin');

        $adminLoginResponse = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => $adminPassword,
            'password_confirmation' => $adminPassword,
        ]);
        $adminToken = $adminLoginResponse->json('token');

        $product = Product::factory()->create();

        $data = [
            'VSSLPR' => $faker->numerify('VSSLPR#####'),
            'name' => $faker->word,
            'amount' => 0,
            'storage_amount' => $faker->numberBetween($min = 1000, $max = 9000),
            'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
            'status' => 'Inactive',
        ];

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->put(route('products.update', $product->id), $data)
            ->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJson($data);
    }

    public function test_can_update_a_product_with_pharmacist_role(): void
    {
        $faker = Factory::create();
        $adminPassword = $faker->password;

        $pharmacist = User::factory()->create([
            'password' => bcrypt($adminPassword),
        ]);

        $pharmacist->assignRole('Pharmacist');

        $pharmacistLoginResponse = $this->post(route('login'), [
            'email' => $pharmacist->email,
            'password' => $adminPassword,
            'password_confirmation' => $adminPassword,
        ]);
        $pharmacistToken = $pharmacistLoginResponse->json('token');

        $product = Product::factory()->create();

        $data = [
            'VSSLPR' => $faker->numerify('VSSLPR#####'),
            'name' => $faker->word,
            'amount' => 0,
            'storage_amount' => $faker->numberBetween($min = 1000, $max = 9000),
            'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
            'status' => 'Inactive',
        ];

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->put(route('products.update', $product->id), $data)
            ->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJson($data);
    }

    public function test_can_show_a_product_with_department_role(): void
    {

        $faker = Factory::create();
        $departmentPassword = $faker->password;

        $department = User::factory()->create([
            'password' => bcrypt($departmentPassword),
        ]);

        $department->assignRole('Department');

        $departmentLoginResponse = $this->post(route('login'), [
            'email' => $department->email,
            'password' => $departmentPassword,
            'password_confirmation' => $departmentPassword,
        ]);
        $departmentToken = $departmentLoginResponse->json('token');

        $product = Product::factory()->create();

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->get(route('products.show', $product->id))
            ->assertStatus(ResponseAlias::HTTP_OK);
    }

    public function test_index_returns_data_in_valid_format_with_department_role(): void
    {

        $faker = Factory::create();
        $departmentPassword = $faker->password;

        $department = User::factory()->create([
            'password' => bcrypt($departmentPassword),
        ]);

        $department->assignRole('Department');

        $departmentLoginResponse = $this->post(route('login'), [
            'email' => $department->email,
            'password' => $departmentPassword,
            'password_confirmation' => $departmentPassword,
        ]);
        $departmentToken = $departmentLoginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $departmentToken");
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

    public function test_search_returns_data_in_valid_format_with_department_role(): void
    {

        $faker = Factory::create();
        $departmentPassword = $faker->password;

        $department = User::factory()->create([
            'password' => bcrypt($departmentPassword),
        ]);

        $department->assignRole('Department');

        $departmentLoginResponse = $this->post(route('login'), [
            'email' => $department->email,
            'password' => $departmentPassword,
            'password_confirmation' => $departmentPassword,
        ]);
        $departmentToken = $departmentLoginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->get(route('products.search', 'a'))
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
