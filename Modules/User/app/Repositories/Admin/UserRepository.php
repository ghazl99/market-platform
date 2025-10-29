<?php

namespace Modules\User\Repositories\Admin;

interface UserRepository
{
    public function index($keyword = null, $statusFilter = null, $roleFilter = null);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($user);

    public function toggleAdminStatus($id, bool $isAdmin);
}
