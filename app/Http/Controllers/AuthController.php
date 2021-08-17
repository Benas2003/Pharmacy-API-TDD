<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{

    public function register(Request $request): Response
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'role'=>[
                'required',
                Rule::in(['Admin', 'Pharmacist', 'Department']),
            ],
        ]);



        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $user->assignRole($fields['role']);


        $response = [
            'user' => $user,
            'message' => 'User was created successfully'
        ];

        return response($response, ResponseAlias::HTTP_CREATED);
    }

    public function login(Request $request): Response
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || $this->notEqual($fields['password'], $user)) {
            return new response([
                'message' => 'Bad data'
            ], ResponseAlias::HTTP_UNAUTHORIZED);

        }

        $token = $user->createToken('PharmacyAPI')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return new response($response, ResponseAlias::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response(null, ResponseAlias::HTTP_NO_CONTENT);
    }

    private function notEqual($password, $user): bool
    {
        return !Hash::check($password, $user->password);
    }


}
