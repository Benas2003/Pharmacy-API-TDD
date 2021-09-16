<?php

namespace App\Domain\User\UseCase;

use App\Domain\User\DTO\LogoutUseCaseDTO\LogoutInput;

class LogoutUseCase
{
    public function execute(LogoutInput $logoutInput): void
    {
        $logoutInput->getAuth()->tokens()->delete();
    }
}
