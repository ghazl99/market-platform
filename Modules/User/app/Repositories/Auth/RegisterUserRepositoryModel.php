<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;

class RegisterUserRepositoryModel implements RegisterUserRepository
{
    public function create(array $data): User
    {
        $user = User::firstOrCreate(
            ['email' => $data['email']], 
            [
                'name' => $data['name'],
                'password' => $data['password'],
            ]
        );

        if (! $user->hasRole($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }
}
