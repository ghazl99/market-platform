<?php

namespace Modules\User\Repositories\Admin;

use Modules\User\Models\User;

class UserModelRepository implements UserRepository
{
    public function index()
    {
        return User::whereHas('roles', fn ($q) => $q->where('name', 'owner'))
            ->with('stores')->get();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->find($id);
        $user->update($data);

        return $user;
    }

    public function delete($id)
    {
        $user = $this->find($id);

        return $user->delete();
    }

    public function toggleAdminStatus($id, bool $isAdmin)
    {
        $user = $this->find($id);
        $user->update(['is_admin' => $isAdmin]);

        return $user;
    }
}
