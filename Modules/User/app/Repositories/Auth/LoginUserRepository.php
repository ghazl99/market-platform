<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;

interface LoginUserRepository
{
    public function findByEmail(string $email): ?User;
}
