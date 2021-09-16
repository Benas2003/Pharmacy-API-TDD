<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Domain\Order\Exceptions\DeliveredOrderStatusException;
use App\Models\Order;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderControllerTest extends TestCase
{
    public const ADMIN_EMAIL = 'Admin@icloud.com';
    public const ADMIN_PASSWORD = 'Admin';

    public const PHARMACIST_EMAIL = 'jonas.jonaitis@icloud.com';
    public const PHARMACIST_PASSWORD = 'JJKK';

    public const DEPARTMENT_EMAIL = 'cr@icloud.com';
    public const DEPARTMENT_PASSWORD = 'CR91';

    public function test_update_order_amount_with_Administrator_role_returns_ok(): void
    {
        sleep(1);
        $order=Order::factory()->create([
            'status'=>'Ordered',
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->put(route('orders.update', [$order->id, 200]))
            ->assertStatus(ResponseAlias::HTTP_OK);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_update_order_amount_with_Pharmacist_role_returns_forbidden(): void
    {
        $order=Order::factory()->create([
            'status'=>'Ordered',
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::PHARMACIST_EMAIL,
            'password' => self::PHARMACIST_PASSWORD,
            'password_confirmation' => self::PHARMACIST_PASSWORD,
        ]);
        $pharmacistToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->put(route('orders.update', [
            'id'=>$order->id,
            'amount'=>100,
        ]))->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

        $this->withHeader("Authorization", "Bearer $pharmacistToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_update_order_amount_with_Department_role_returns_forbidden(): void
    {
        $order=Order::factory()->create([
            'status'=>'Ordered',
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::DEPARTMENT_EMAIL,
            'password' => self::DEPARTMENT_PASSWORD,
            'password_confirmation' => self::DEPARTMENT_PASSWORD,
        ]);
        $departmentToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->put(route('orders.update', [
            'id'=>$order->id,
            'amount'=>100,
        ]))->assertStatus(ResponseAlias::HTTP_FORBIDDEN);

        $this->withHeader("Authorization", "Bearer $departmentToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_update_order_amount_with_Admin_role_and_Delivered_status_returns_exception(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(DeliveredOrderStatusException::class);
        $this->expectExceptionCode(ResponseAlias::HTTP_BAD_REQUEST);

        $order=Order::factory()->create([
            'status'=>'Delivered',
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $loginResponse->json('token');

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->put(route('orders.update', ['id'=>$order->id,'amount'=>100,]));

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }
}
