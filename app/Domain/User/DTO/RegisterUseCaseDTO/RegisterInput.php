<?php

namespace App\Domain\User\DTO\RegisterUseCaseDTO;

use App\Domain\User\Exceptions\InvalidRoleInputException;
use App\Domain\User\Validator\RegisterValidator;
use App\Domain\User\Validator\UserValidatorRules;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterInput
{
    public const ROLES = array('Administrator', 'Pharmacist', 'Department');
    private User $user;
    private string $role;

    public function __construct(Request $request, string $role, RegisterValidator $registerValidator)
    {
        $registerValidator->validateRegisterInputs($request, new UserValidatorRules());

        if (!in_array($role, self::ROLES))
        {
            throw new InvalidRoleInputException();
        }

        $this->role = $role;
        $this->user = new User($request->all());
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
}
