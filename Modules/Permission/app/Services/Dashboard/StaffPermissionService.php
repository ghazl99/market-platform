<?php

namespace Modules\Permission\Services\Dashboard;

use Modules\Permission\Repositories\Dashboard\StaffPermissionRepository;
use Modules\User\Models\User;

class StaffPermissionService
{
    public function __construct(
        protected StaffPermissionRepository $staffPermissionRepository
    ) {}

    public function getAllPermissions()
    {
        return $this->staffPermissionRepository->all();
    }

    public function assignPermissionsToUser(User $user, array $permissions)
    {
        $user->syncPermissions($permissions); // تحديث الصلاحيات كلها
    }

    public function addNewPermission(string $name)
    {
        return $this->staffPermissionRepository->create(['name' => $name, 'guard_name' => 'web']);
    }
}
