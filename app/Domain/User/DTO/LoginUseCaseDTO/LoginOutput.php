<?php

namespace App\Domain\User\DTO\LoginUseCaseDTO;

use App\Models\User;

class LoginOutput
{
    private User $user;
    private string $token;

    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    public function toArray(): array
    {
        return array('User'=>$this->user, 'token'=>$this->token);
    }
}
