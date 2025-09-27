<?php

namespace Modules\Permission\Repositories\Dashboard;

interface StaffPermissionRepository
{
    public function all();

    public function create(array $data);
}
