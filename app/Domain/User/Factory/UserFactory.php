<?php

namespace App\Domain\User\Factory;

use Faker\Generator;

class UserFactory
{
    private Generator $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function fakeUser(): array
    {
        $user = [];

        $user['name'] = $this->generator->name();
        $user['email'] = $this->generator->unique()->safeEmail();
        $user['password'] = bcrypt($this->generator->password);

        return $user;
    }
}
