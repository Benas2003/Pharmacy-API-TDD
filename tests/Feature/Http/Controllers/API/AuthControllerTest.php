<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Domain\User\Exceptions\InvalidCredentialsInputException;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public const ADMIN_EMAIL = 'Admin@icloud.com';
    public const ADMIN_PASSWORD = 'Admin';
    public const WRONG_ADMIN_PASSWORD = 'dmin';
    public const WRONG_ADMIN_EMAIL = 'dmin@icloud.com';

    public function test_user_register_returns_created(): void
    {
        $adminLoginResponse = $this->post(route('login'), [
            'email' => 'admin@icloud.com',
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $adminToken = $adminLoginResponse->json('token');

        $user = User::factory()->make();

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('register', "Department"), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ])
            ->assertStatus(ResponseAlias::HTTP_CREATED);

        $this->withHeader("Authorization", "Bearer $adminToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_user_login_returns_ok(): void
    {
        $response = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ])
            ->assertStatus(ResponseAlias::HTTP_OK);

        $userToken = $response->json('token');
        $this->withHeader("Authorization", "Bearer $userToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_user__login_with_wrong_email_returns_exception(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidCredentialsInputException::class);
        $this->expectExceptionCode(ResponseAlias::HTTP_UNAUTHORIZED);

        $this->post(route('login'), [
            'email' => self::WRONG_ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
    }

    public function test_user_login_with_wrong_password_returns_exception(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidCredentialsInputException::class);
        $this->expectExceptionCode(ResponseAlias::HTTP_UNAUTHORIZED);

        $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::WRONG_ADMIN_PASSWORD,
            'password_confirmation' => self::WRONG_ADMIN_PASSWORD,
        ]);
    }

    public function test_user_logout_returns_no_content(): void
    {
        $userLoginResponse = $this->post(route('login'), [
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'password_confirmation' => self::ADMIN_PASSWORD,
        ]);
        $userToken = $userLoginResponse->json('token');
        $this->withHeader("Authorization", "Bearer $userToken");
        $this->post(route('logout',))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }
}
