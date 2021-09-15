<?php

namespace App\Domain\User\Validator;

use App\Domain\User\Exceptions\InvalidEmailInputException;
use App\Domain\User\Exceptions\InvalidNameInputException;
use App\Domain\User\Exceptions\InvalidPasswordInputException;
use App\Domain\User\Exceptions\InvalidRoleInputException;
use Illuminate\Http\Request;

class RegisterValidator
{
    public function validateRegisterInputs(Request $request): void
    {
        $rules = new UserValidatorRules();

        $nameValidator = $rules->nameValidator($request);
        $emailValidator = $rules->registerEmailValidator($request);
        $passwordValidator = $rules->passwordValidator($request);

        if ($nameValidator->fails()) {
            throw new InvalidNameInputException();
        }

        if ($emailValidator->fails()) {
            throw new InvalidEmailInputException();
        }

        if ($passwordValidator->fails()) {
            throw new InvalidPasswordInputException();
        }
    }
}
