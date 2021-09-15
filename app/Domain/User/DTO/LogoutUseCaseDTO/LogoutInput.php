<?php

namespace App\Domain\User\DTO\LogoutUseCaseDTO;

use Illuminate\Contracts\Auth\Authenticatable;

class LogoutInput
{
    private Authenticatable $auth;

    public function __construct(Authenticatable $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return Authenticatable
     */
    public function getAuth(): Authenticatable
    {
        return $this->auth;
    }


}
