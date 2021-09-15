<?php

namespace App\Domain\User\Repository;

use App\Models\User;

class UserRepository
{
    public function findByEmail($email): User|null
    {
        return User::where('email', $email)->first();
    }

    public function getUserNameById($id): string
    {
        return User::select('name')->where('id', $id)->get();
    }
}
