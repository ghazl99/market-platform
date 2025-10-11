<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;

interface ProfileRepository
{
    public function update(User $user, array $data): User ;
}
