<?php

namespace Modules\Permission\Repositories\Dashboard;

use Spatie\Permission\Models\Permission;

class StaffPermissionModelRepository implements StaffPermissionRepository
{
    public function all()
    {
        return Permission::all();
    }

    public function create(array $data)
    {
        return Permission::create($data);
    }
}
