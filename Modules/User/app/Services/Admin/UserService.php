<?php

namespace Modules\User\Services\Admin;

use Modules\User\Repositories\Admin\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function getAllUsers($keyword = null, $statusFilter = null, $roleFilter = null)
    {
        return $this->userRepository->index($keyword, $statusFilter, $roleFilter);
    }
}
