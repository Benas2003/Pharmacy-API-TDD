<?php

namespace App\Domain\User\UseCase;

use App\Domain\User\Validator\RegisterValidator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RegisterUseCase
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(): JsonResponse
    {
        $registerValidator = new RegisterValidator();
        $registerValidator->validateRegisterInputs($this->request);

        $user = User::create([
            'name' => $this->request['name'],
            'email' => $this->request['email'],
            'password' => bcrypt($this->request['password']),
        ]);

        $user->assignRole($this->request['role']);


        $response = [
            'user' => $user,
            'message' => 'User was created successfully'
        ];

        return new JsonResponse($response, ResponseAlias::HTTP_CREATED);
    }
}
