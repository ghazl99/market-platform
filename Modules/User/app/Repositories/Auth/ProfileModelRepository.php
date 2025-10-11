<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;

class ProfileModelRepository implements ProfileRepository
{
    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }
}
