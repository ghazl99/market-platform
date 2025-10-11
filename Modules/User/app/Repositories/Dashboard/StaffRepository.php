<?php

namespace Modules\User\Repositories\Dashboard;

use Modules\User\Models\User;

interface StaffRepository
{
    public function index(?string $search = null): mixed;

    public function create(array $data): User;

    public function toggleActive(User $user, int $storeId): User;
}
