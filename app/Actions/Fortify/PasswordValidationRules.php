<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
     protected function passwordRules()
    {
        return [
            'required',
            'string',
            'confirmed',
            Password::min(8)
                ->mixedCase()     // Upper + lowercase
                ->letters()       // At least one letter
                ->numbers()       // At least one number
                ->symbols()       // At least one symbol
                ->uncompromised() // Not in data breaches
        ];
    }
}
