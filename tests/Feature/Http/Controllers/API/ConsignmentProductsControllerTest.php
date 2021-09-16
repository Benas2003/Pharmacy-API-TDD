<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Domain\Consignment\Exceptions\InvalidStatusException;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ConsignmentProductsControllerTest extends TestCase
{
    public const ADMIN_EMAIL = 'Admin@icloud.com';
    public const ADMIN_PASSWORD = 'Admin';

    public const DEPARTMENT_USER_ID = 3;
    public const DEPARTMENT_EMAIL = 'cr@icloud.com';
    public const DEPARTMENT_PASSWORD = 'CR91';

    public function test_update_product_amount_in_Created_consignment_with_Department_role_returns_ok():void
    {
        sleep(1.5);
        $consignment = Consignment::factory()->create([
            'department_id'=>self::DEPARTMENT_USER_ID,
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

    public function test_update_product_amount_in_Processed_consignment_with_Department_role_returns_exception():void
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidStatusException::class);
        $this->expectExceptionCode(ResponseAlias::HTTP_BAD_REQUEST);

        $consignment = Consignment::factory()->create([
            'department_id'=>self::DEPARTMENT_USER_ID,
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
        $this->put(route('consignments.products.update', $consignment_product->id),['amount'=>200]);
    }

    public function test_update_product_amount_in_Given_away_consignment_with_Department_role_returns_exception():void
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidStatusException::class);
        $this->expectExceptionCode(ResponseAlias::HTTP_BAD_REQUEST);

        $consignment = Consignment::factory()->create([
            'department_id'=>self::DEPARTMENT_USER_ID,
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
        $this->put(route('consignments.products.update', $consignment_product->id),['amount'=>200]);
    }

    public function test_update_product_amount_in_Created_consignment_with_Administrator_role_returns_forbidden():void
    {
        $consignment = Consignment::factory()->create([
            'department_id'=>self::DEPARTMENT_USER_ID,
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
