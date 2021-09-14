<?php

namespace App\Domain\User\Validator;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserValidatorRules
{
    public function loginEmailValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {

        return Validator::make($request->all(), [
            'email' => 'required|string',
        ]);
    }

    public function passwordValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'password' => 'required|string|confirmed',
        ]);
    }

    public function nameValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
    }

    public function registerEmailValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'email' => 'required|string|unique:users,email',
        ]);
    }

    public function roleValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'role'=>[
                'required',
                Rule::in(['Admin', 'Pharmacist', 'Department']),
            ],
        ]);
    }
}
