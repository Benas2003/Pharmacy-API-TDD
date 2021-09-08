<?php

namespace App\Domain\User\Factory;

use Faker\Factory;

class UserFactory
{
    public function fakeUser(): array
    {
        $faker = Factory::create();

        $user = [];

        $user['name'] = $faker->name();
        $user['email'] = $faker->unique()->safeEmail();
        $user['password'] = bcrypt($faker->password);

        return $user;
    }
}
