<?php

namespace App\Domain\User\UseCase;

use App\Domain\User\DTO\RegisterUseCaseDTO\RegisterInput;
use App\Domain\User\DTO\RegisterUseCaseDTO\RegisterOutput;
use App\Models\User;

class RegisterUseCase
{
    public function execute(RegisterInput $registerInput): RegisterOutput
    {
        $user = User::create($registerInput->getUser()->toArray());

        $user->assignRole($registerInput->getRole());

        return new RegisterOutput($user, 'User was created successfully');
    }
}
