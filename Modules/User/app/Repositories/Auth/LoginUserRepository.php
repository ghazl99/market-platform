<?php

namespace Modules\User\Repositories\Auth;

use Modules\User\Models\User;
use Modules\User\Models\FcmToken;

interface LoginUserRepository
{
    public function findByEmail(string $email): ?User;
    public function createOrUpdateToken(array $data): FcmToken;
}
