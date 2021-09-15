<?php

namespace App\Domain\User\DTO\LoginUseCaseDTO;

use App\Domain\User\Validator\LoginValidator;
use Illuminate\Http\Request;

class LoginInput
{
    private string $email;
    private string $password;

    public function __construct(Request $request)
    {
        $userValidator = new LoginValidator();
        $userValidator->validateLoginInputs($request);

        $this->email = $request->email;
        $this->password = $request->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


}
