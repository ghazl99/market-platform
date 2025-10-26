<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;

interface RegisterUserRepository
{
    public function create(array $data): User;
    public function createOwner(array $data): User;
}
