<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Domain\Consignment\Exceptions\InvalidConsignmentStatusException;
use App\Models\Consignment;
use App\Models\ConsignmentProduct;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ConsignmentControllerTest extends TestCase
{
    public const ADMIN_EMAIL = 'Admin@icloud.com';
    public const ADMIN_PASSWORD = 'Admin';

    public const PHARMACIST_EMAIL = 'jonas.jonaitis@icloud.com';
    public const PHARMACIST_PASSWORD = 'JJKK';

    public const DEPARTMENT_USER_ID = 3;
    public const DEPARTMENT_EMAIL = 'cr@icloud.com';
    public const DEPARTMENT_PASSWORD = 'CR91';


    public function test_create_a_consignment_with_Department_role_returns_created(): void
    {
        sleep(1.5);
        $loginResponse = $this->post(route('login'), [
            'email' => self::DEPARTMENT_EMAIL,
            'password' => self::DEPARTMENT_PASSWORD,
            'password_confirmation' => self::DEPARTMENT_PASSWORD,
        ]);
        $departmentToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->post(route('consignments.store'), [
            1=>[
                "id"=>2,
                "amount"=>100,
            ]
        ])->assertStatus(ResponseAlias::HTTP_CREATED);

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_create_a_consignment_with_Admin_role_returns_forbidden(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('consignments.store'), [
            1=>[
                "id"=>2,
                "amount"=>100,
            ]
        ])
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_create_a_consignment_with_Pharmacist_role_returns_forbidden(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::PHARMACIST_EMAIL,
            'password' => self::PHARMACIST_PASSWORD,
            'password_confirmation' => self::PHARMACIST_PASSWORD,
        ]);
        $pharmacistToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('consignments.store'), [
            1=>[
                "id"=>2,
                "amount"=>100,
            ]
        ])
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_update_a_consignment_with_Pharmacist_role_returns_ok(): void
    {
        $consignment = Consignment::factory()->create([
            'department_id'=> self::DEPARTMENT_USER_ID,
            'status'=>'Created'
        ]);
        ConsignmentProduct::factory(2)->create([
            'consignment_id'=>$consignment->id,
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::PHARMACIST_EMAIL,
            'password' => self::PHARMACIST_PASSWORD,
            'password_confirmation' => self::PHARMACIST_PASSWORD,
        ]);
        $pharmacistToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->put(route('consignments.update', $consignment->id),['status'=>'Processed'])
            ->assertStatus(ResponseAlias::HTTP_OK);

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_update_a_consignment_with_Admin_role_returns_forbidden(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->put(route('consignments.update', 2),['status'=>'Given away'])
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_show_a_product_returns_ok_and_data(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->get(route('consignments.show', self::DEPARTMENT_USER_ID))
            ->assertStatus(ResponseAlias::HTTP_OK)->assertJsonStructure([
                'Status',
                'Products'
            ]);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_show_products_returns_ok(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->get(route('consignments.index'))
            ->assertStatus(ResponseAlias::HTTP_OK);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_delete_products_with_Created_status_returns_no_content(): void
    {

        $consignment = Consignment::factory()->create([
            'department_id'=> self::DEPARTMENT_USER_ID,
            'status'=>'Created'
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->delete(route('consignments.destroy', $consignment->id))
            ->assertStatus(ResponseAlias::HTTP_NO_CONTENT)->assertNoContent();

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_delete_products_with_Given_away_status_returns_bad_request(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidConsignmentStatusException::class);
        $this->expectExceptionCode(ResponseAlias::HTTP_BAD_REQUEST);

        $consignment = Consignment::factory()->create([
            'department_id'=> self::DEPARTMENT_USER_ID,
            'status'=>'Given away'
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->delete(route('consignments.destroy', $consignment->id));

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_delete_products_with_Processed_status_returns_bad_request(): void
    {

        $this->withoutExceptionHandling();
        $this->expectException(InvalidConsignmentStatusException::class);
        $this->expectExceptionCode(ResponseAlias::HTTP_BAD_REQUEST);

        $consignment = Consignment::factory()->create([
            'department_id'=> self::DEPARTMENT_USER_ID,
            'status'=>'Processed'
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->delete(route('consignments.destroy', $consignment->id));

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_delete_products_with_Pharmacist_role_returns_forbidden(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::PHARMACIST_EMAIL,
            'password' => self::PHARMACIST_PASSWORD,
            'password_confirmation' => self::PHARMACIST_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->delete(route('consignments.destroy', 2))
            ->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }
}
