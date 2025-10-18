<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;

class RegisterUserRepositoryModel implements RegisterUserRepository
{
    public function create(array $data): User
    {
        $user = User::create($data);
        $user->assignRole($data['role']);

        return $user;
    }
}
