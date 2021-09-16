<?php

namespace App\Http\Controllers;

use App\Domain\User\DTO\LoginUseCaseDTO\LoginInput;
use App\Domain\User\DTO\LogoutUseCaseDTO\LogoutInput;
use App\Domain\User\DTO\RegisterUseCaseDTO\RegisterInput;
use App\Domain\User\UseCase\LoginUseCase;
use App\Domain\User\UseCase\LogoutUseCase;
use App\Domain\User\UseCase\RegisterUseCase;
use App\Domain\User\Validator\LoginValidator;
use App\Domain\User\Validator\RegisterValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private LogoutUseCase $logoutUseCase;
    private RegisterUseCase $registerUseCase;
    private LoginUseCase $loginUseCase;

    public function __construct(LogoutUseCase $logoutUseCase, RegisterUseCase $registerUseCase, LoginUseCase $loginUseCase)
    {
        $this->logoutUseCase = $logoutUseCase;
        $this->registerUseCase = $registerUseCase;
        $this->loginUseCase = $loginUseCase;
    }

    public function register(Request $request, string $role): JsonResponse
    {
        $registerInput = new RegisterInput($request, $role, new RegisterValidator());
        return new JsonResponse($this->registerUseCase->execute($registerInput)->toArray(), 201);
    }

    public function login(Request $request): JsonResponse
    {
        $loginInput = new LoginInput($request, new LoginValidator());
        return new JsonResponse($this->loginUseCase->execute($loginInput)->toArray(), 200);
    }

    public function logout(): JsonResponse
    {
        $logoutInput = new LogoutInput(Auth::user());
        $this->logoutUseCase->execute($logoutInput);
        return new JsonResponse(null, 204);
    }
}
