<?php

namespace Tests\Feature\Http\Controllers\API;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ConsignmentControllerTest extends TestCase
{
    public const ADMIN_EMAIL = 'Admin@icloud.com';
    public const ADMIN_PASSWORD = 'Admin';

    public const PHARMACIST_EMAIL = 'jonas.jonaitis@icloud.com';
    public const PHARMACIST_PASSWORD = 'JJKK';

    public const DEPARTMENT_EMAIL = 'cr@icloud.com';
    public const DEPARTMENT_PASSWORD = 'CR91';

    public function test_can_create_a_consignment_with_Department_role(): void
    {
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

    public function test_can_not_create_a_consignment_with_Admin_role(): void
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

    public function test_can_not_create_a_consignment_with_Pharmacist_role(): void
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

    public function test_can_update_a_consignment_with_Pharmacist_role(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::PHARMACIST_EMAIL,
            'password' => self::PHARMACIST_PASSWORD,
            'password_confirmation' => self::PHARMACIST_PASSWORD,
        ]);
        $pharmacistToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->put(route('consignments.update', 2),['status'=>'Processed'])
            ->assertStatus(ResponseAlias::HTTP_OK);

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_can_update_a_consignment_with_Admin_role(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->put(route('consignments.update', 2),['status'=>'Given away'])
            ->assertStatus(ResponseAlias::HTTP_OK);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_can_show_a_product(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->get(route('consignments.show', 3))
            ->assertStatus(ResponseAlias::HTTP_OK)->assertJsonStructure([
                'Department name',
                'Status',
                'Products:'
            ]);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }
}
