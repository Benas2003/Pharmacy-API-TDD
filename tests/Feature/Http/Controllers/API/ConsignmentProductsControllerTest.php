<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ConsignmentProductsControllerTest extends TestCase
{

    public const ADMIN_EMAIL = 'Admin@icloud.com';
    public const ADMIN_PASSWORD = 'Admin';

    public const DEPARTMENT_EMAIL = 'cr@icloud.com';
    public const DEPARTMENT_PASSWORD = 'CR91';

    public function test_can_update_product_amount_in_Created_consignment_with_Department_role():void
    {
        $consignment = Consignment::factory()->create([
            'status'=>'Created',
        ]);
        $consignment_product = ConsignmentProduct::factory()->create([
            'consignment_id'=>$consignment->id,
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::DEPARTMENT_EMAIL,
            'password' => self::DEPARTMENT_PASSWORD,
            'password_confirmation' => self::DEPARTMENT_PASSWORD,
        ]);
        $departmentToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->put(route('consignments.products.update', $consignment_product->id),['amount'=>200])
            ->assertStatus(ResponseAlias::HTTP_OK);
    }

    public function test_can_not_update_product_amount_in_Processed_consignment_with_Department_role():void
    {
        $consignment = Consignment::factory()->create([
        'status'=>'Processed',
        ]);
        $consignment_product = ConsignmentProduct::factory()->create([
        'consignment_id'=>$consignment->id,
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::DEPARTMENT_EMAIL,
            'password' => self::DEPARTMENT_PASSWORD,
            'password_confirmation' => self::DEPARTMENT_PASSWORD,
        ]);
        $departmentToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->put(route('consignments.products.update', $consignment_product->id),['amount'=>200])
            ->assertStatus(ResponseAlias::HTTP_METHOD_NOT_ALLOWED);
    }

    public function test_can_not_update_product_amount_in_Given_away_consignment_with_Department_role():void
    {
        $consignment = Consignment::factory()->create([
            'status'=>'Given away',
        ]);
        $consignment_product = ConsignmentProduct::factory()->create([
            'consignment_id'=>$consignment->id,
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::DEPARTMENT_EMAIL,
            'password' => self::DEPARTMENT_PASSWORD,
            'password_confirmation' => self::DEPARTMENT_PASSWORD,
        ]);
        $departmentToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->put(route('consignments.products.update', $consignment_product->id),['amount'=>200])
            ->assertStatus(ResponseAlias::HTTP_METHOD_NOT_ALLOWED);
    }

    public function test_can_not_update_product_amount_in_Created_consignment_with_Administrator_role():void
    {
        $consignment = Consignment::factory()->create([
            'status'=>'Given away',
        ]);
        $consignment_product = ConsignmentProduct::factory()->create([
            'consignment_id'=>$consignment->id,
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->put(route('consignments.products.update', $consignment_product->id),['amount'=>200])
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);
    }
}
