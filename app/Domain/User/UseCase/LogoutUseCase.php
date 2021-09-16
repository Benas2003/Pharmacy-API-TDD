<?php

namespace App\Domain\User\UseCase;

use App\Domain\User\DTO\LogoutUseCaseDTO\LogoutInput;

class LogoutUseCase
{
    public function execute(LogoutInput $logoutInput): void
    {
        $user = $logoutInput->getAuth();
        $user->tokens()->delete();
    }
}
