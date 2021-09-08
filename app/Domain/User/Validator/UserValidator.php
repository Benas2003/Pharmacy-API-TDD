<?php

namespace App\Domain\User\Validator;


use App\Domain\User\Exceptions\InvalidCredentialsInputException;
use App\Domain\User\Exceptions\InvalidEmailInputException;
use App\Domain\User\Exceptions\InvalidNameInputException;
use App\Domain\User\Exceptions\InvalidPasswordInputException;
use App\Domain\User\Exceptions\InvalidRoleInputException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserValidator
{
    public function validateRegisterInputs(Request $request): void
    {
        $nameValidator = $this->nameValidator($request);
        $emailValidator = $this->registerEmailValidator($request);
        $passwordValidator = $this->passwordValidator($request);
        $roleValidator = $this->roleValidator($request);

        if ($nameValidator->fails()) {
            throw new InvalidNameInputException();
        }

        if ($emailValidator->fails()) {
            throw new InvalidEmailInputException();
        }

        if ($passwordValidator->fails()) {
            throw new InvalidPasswordInputException();
        }

        if ($roleValidator->fails()) {
            throw new InvalidRoleInputException();
        }
    }

    public function validateLoginInputs(Request $request): void
    {
        $emailValidator = $this->loginEmailValidator($request);

        $passwordValidator = $this->passwordValidator($request);

        if ($emailValidator->fails()) {
            throw new InvalidCredentialsInputException();
        }

        if ($passwordValidator->fails()) {
            throw new InvalidPasswordInputException();
        }
    }

    protected function loginEmailValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {

        return Validator::make($request->all(), [
            'email' => 'required|string',
        ]);
    }

    protected function passwordValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'password' => 'required|string|confirmed',
        ]);
    }

    protected function nameValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
    }

    protected function registerEmailValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'email' => 'required|string|unique:users,email',
        ]);
    }

    protected function roleValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'role'=>[
                'required',
                Rule::in(['Admin', 'Pharmacist', 'Department']),
            ],
        ]);
    }
}
