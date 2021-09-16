<?php

namespace App\Domain\User\Validator;

use App\Domain\User\Exceptions\InvalidCredentialsInputException;
use App\Domain\User\Exceptions\InvalidPasswordInputException;
use Illuminate\Http\Request;

class LoginValidator
{
    public function validateLoginInputs(Request $request, UserValidatorRules $rules): void
    {
        $emailValidator = $rules->loginEmailValidator($request);

        $passwordValidator = $rules->passwordValidator($request);

        if ($emailValidator->fails()) {
            throw new InvalidCredentialsInputException();
        }

        if ($passwordValidator->fails()) {
            throw new InvalidPasswordInputException();
        }
    }
}
