<?php

namespace App\Http\Controllers;

use App\Domain\User\UseCase\LoginUseCase;
use App\Domain\User\UseCase\LogoutUseCase;
use App\Domain\User\UseCase\RegisterUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function register(Request $request): JsonResponse
    {
        $registerUseCase = new RegisterUseCase($request);
        return $registerUseCase->execute();
    }

    public function login(Request $request): JsonResponse
    {
        $loginUseCase = new LoginUseCase($request);
        return $loginUseCase->execute();
    }

    public function logout(): JsonResponse
    {
        $logoutUseCase = new LogoutUseCase();
        return $logoutUseCase->execute();
    }


}
