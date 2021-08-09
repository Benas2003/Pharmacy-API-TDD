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
        $user = User::factory()->make();

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

//    public function test_user_can_logout(): void
//    {
//
//    }
}
