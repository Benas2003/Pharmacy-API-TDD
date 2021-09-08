<?php

namespace App\Domain\User\UseCase;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class LogoutUseCase
{
    public function execute(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return new JsonResponse(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
