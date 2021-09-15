<?php

namespace App\Domain\User\UseCase;

use App\Domain\User\DTO\LogoutUseCaseDTO\LogoutInput;

class LogoutUseCase
{
    public function execute(LogoutInput $logoutInput): void
    {
        $user = $logoutInput->getAuth();
//        $user = Auth::user();
//        dd($user, auth());
        $user->tokens()->delete();
//        auth()->user()->tokens()->delete();

    }
}
