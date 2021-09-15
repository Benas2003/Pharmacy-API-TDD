<?php

namespace App\Domain\User\Repository;

use App\Models\User;

class UserRepository
{
    public function findByEmail($email): User|null
    {
        return User::where('email', $email)->first();
    }
}
