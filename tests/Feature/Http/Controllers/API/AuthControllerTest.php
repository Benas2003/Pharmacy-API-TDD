<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\User;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function test_user_can_register(): void
    {
        $faker = Factory::create();
        $adminPassword = $faker->password;

        $admin = User::factory()->create([
            'password'=>bcrypt($adminPassword),
        ]);

        $adminLoginResponse = $this->post(route('login'),[
            'email'=> $admin->email,
            'password'=>$adminPassword,
            'password_confirmation'=>$adminPassword,
        ]);
        $adminToken = $adminLoginResponse->json('token');

        $user = User::factory()->make();

        $this->withHeader("Authorization","Bearer $adminToken");
        $response = $this->post(route('register'), [
            'name'=>$user->name,
            'email'=>$user->email,
            'password'=>$user->password,
            'password_confirmation'=>$user->password,
        ])
            ->assertStatus(ResponseAlias::HTTP_CREATED);

        $response->assertJsonStructure([
            'user'=>[
                'created_at',
                'email',
                'id',
                'name',
                'updated_at',
            ],
            'message'
        ]);

        $this->withHeader("Authorization","Bearer $adminToken");
        $this->post(route('logout', ))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_user_can_login(): void
    {
        $faker = Factory::create();
        $password = $faker->password;

        $user = User::factory()->create([
            'password'=>bcrypt($password)
        ]);

         $response = $this->post(route('login'),[
            'email'=>$user->email,
            'password'=>$password,
            'password_confirmation'=>$password,
         ])
             ->assertStatus(ResponseAlias::HTTP_CREATED);

         $response->assertJsonStructure([
             'user'=>[
                 'created_at',
                 'email',
                 'id',
                 'name',
                 'updated_at',
             ],
             'token'
         ]);

        $userToken = $response->json('token');
        $this->withHeader("Authorization","Bearer $userToken");
        $this->post(route('logout', ))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }

    public function test_user_can_not_login_with_wrong_email(): void
    {
        $faker = Factory::create();
        $password = $faker->password;

        $user = User::factory()->create([
            'password'=>bcrypt($password)
        ]);

        $response = $this->post(route('login'),[
            'email'=>'email@email.com',
            'password'=>$password,
            'password_confirmation'=>$password,
        ])
            ->assertStatus(ResponseAlias::HTTP_UNAUTHORIZED)
            ->assertExactJson([
                'message'=>'Bad data'
            ]);
    }

    public function test_user_can_not_login_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'),[
            'email'=>$user->email,
            'password'=>'BigBen',
            'password_confirmation'=>'BigBen',
        ])
            ->assertStatus(ResponseAlias::HTTP_UNAUTHORIZED)
            ->assertExactJson([
                'message'=>'Bad data'
            ]);
    }

    public function test_user_can_logout(): void
    {
        $faker = Factory::create();
        $adminPassword = $faker->password;

        $admin = User::factory()->create([
            'password'=>bcrypt($adminPassword),
        ]);

        $userLoginResponse = $this->post(route('login'),[
            'email'=> $admin->email,
            'password'=>$adminPassword,
            'password_confirmation'=>$adminPassword,
        ]);
        $userToken = $userLoginResponse->json('token');
        $this->withHeader("Authorization","Bearer $userToken");
        $this->post(route('logout', ))->assertNoContent()->assertStatus(ResponseAlias::HTTP_NO_CONTENT);
    }
}
