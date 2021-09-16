<?php

namespace App\Domain\User\UseCase;

use App\Domain\User\DTO\LoginUseCaseDTO\LoginInput;
use App\Domain\User\DTO\LoginUseCaseDTO\LoginOutput;
use App\Domain\User\Exceptions\InvalidCredentialsInputException;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Validator\NotEqual;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class LoginUseCase
{
    private UserRepository $userRepository;
    private NotEqual $passwordValidator;

    public function __construct(UserRepository $userRepository, NotEqual $passwordValidator)
    {
        $this->userRepository = $userRepository;
        $this->passwordValidator = $passwordValidator;
    }

    public function execute(LoginInput $loginInput): LoginOutput
    {
        $user = $this->userRepository->findByEmail($loginInput->getEmail());

        if (!$user || $this->passwordValidator->execute($loginInput->getPassword(), $user)) {
            throw new InvalidCredentialsInputException(ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('PharmacyAPI')->plainTextToken;
        return new LoginOutput($user, $token);

    }
}
