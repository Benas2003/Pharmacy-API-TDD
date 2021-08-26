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

    /**
     * @throws \JsonException
     */
    public function test_can_create_a_consignment_with_Department_role(): void
    {
        $loginResponse = $this->post(route('login'), [
            'email' => self::DEPARTMENT_EMAIL,
            'password' => self::DEPARTMENT_PASSWORD,
            'password_confirmation' => self::DEPARTMENT_PASSWORD,
        ]);
        $departmentToken = $loginResponse->json('token');

        $data = json_encode([

        ], JSON_THROW_ON_ERROR);


        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->post(route('consignments.store'), [
            1=>[
                "id"=>2,
                "amount"=>100,
            ]
        ])
            ->assertStatus(ResponseAlias::HTTP_CREATED);

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }
}
