<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;
use Modules\User\Models\FcmToken;

class LoginUserRepositoryModel implements LoginUserRepository
{
    public function findByEmailAndStore(string $email, int $storeId): ?User
    {
        return User::where('email', $email)
            ->whereHas('stores', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->first();
    }

    public function createOrUpdateToken(array $data): FcmToken
    {
        return FcmToken::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'device_name' => $data['device_name'] ?? 'Unknown',
            ],
            [
                'store_id' => $data['store_id'],
                'token' => $data['token'] // يحدث التوكن دائماً
            ]
        );
    }
}
