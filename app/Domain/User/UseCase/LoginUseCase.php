<?php

namespace App\Domain\User\UseCase;

use App\Domain\User\Exceptions\InvalidCredentialsInputException;
use App\Domain\User\Validator\NotEqual;
use App\Domain\User\Validator\UserValidator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class LoginUseCase
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(): JsonResponse
    {
        $userValidator = new UserValidator();
        $passwordValidator = new NotEqual();
        $userValidator->validateLoginInputs($this->request);

        $user = User::where('email', $this->request['email'])->first();



        if (!$user || $passwordValidator->execute($this->request['password'], $user)) {
            throw new InvalidCredentialsInputException(ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('PharmacyAPI')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return new JsonResponse($response);
    }
}
