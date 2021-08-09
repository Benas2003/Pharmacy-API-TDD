<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function test_user_can_register(): void
    {
        $user = User::factory()->make();

        $this->post(route('register'), [
            'name'=>$user->name,
            'email'=>$user->email,
            'password'=>$user->password,
            'password_confirmation'=>$user->password,
        ])
            ->assertStatus(201);
    }

//    public function test_user_can_login(): void
//    {
//
//    }

//    public function test_user_can_logout(): void
//    {
//
//    }
}
