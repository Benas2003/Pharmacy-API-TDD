<?php

namespace App\Domain\User\Validator;

use Illuminate\Support\Facades\Hash;

class NotEqual
{
    public function execute($password, $user): bool
    {
        return !Hash::check($password, $user->password);
    }
}
