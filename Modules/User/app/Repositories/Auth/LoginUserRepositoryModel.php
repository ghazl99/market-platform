<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;

class LoginUserRepositoryModel implements LoginUserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
