<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'nama' => Str::headline(Str::before($input['email'], '@')),
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => 'umum',
        ]);

        $user->umumProfile()->create([
            'no_ktp' => null,
        ]);
        $user->profile()->create([
            'no_wa' => null,
        ]);

        return $user;
    }
}
