<?php

namespace App\Domain\User\DTO\RegisterUseCaseDTO;

use App\Models\User;

class RegisterOutput
{
    private User $user;
    private string $message;

    public function __construct(User $user, string $message)
    {
        $this->user = $user;
        $this->message = $message;
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
    public function getMessage(): string
    {
        return $this->message;
    }

    public function toArray(): array
    {
        return $this->user->toArray();
    }
}
