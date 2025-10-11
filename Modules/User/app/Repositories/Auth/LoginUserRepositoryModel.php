<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;
use Modules\User\Models\FcmToken;

class LoginUserRepositoryModel implements LoginUserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createOrUpdateToken(array $data): FcmToken
    {
        return FcmToken::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'device_name' => $data['device_name'] ?? 'Unknown',
            ],
            [
                'token' => $data['token'] // يحدث التوكن دائماً
            ]
        );
    }
}
