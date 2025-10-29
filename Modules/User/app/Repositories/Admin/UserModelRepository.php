<?php

namespace Modules\User\Repositories\Admin;

use Modules\User\Models\User;

class UserModelRepository implements UserRepository
{
    public function index($keyword = null, $statusFilter = null, $roleFilter = null)
    {
        $query = User::with(['stores', 'roles']);

        // Search functionality
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // Status filter
        if ($statusFilter) {
            switch ($statusFilter) {
                case 'active':
                    $query->whereNotNull('email_verified_at');
                    break;
                case 'inactive':
                    $query->whereNull('email_verified_at');
                    break;
                case 'pending':
                    $query->whereNull('email_verified_at');
                    break;
            }
        }

        // Role filter
        if ($roleFilter) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $roleFilter));
        } else {
            // Default: show all users with roles (owners, staff, customers)
            $query->whereHas('roles');
        }

        return $query->orderBy('created_at', 'desc')->get();
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

    public function delete($user)
    {
        return $user->delete();
    }

    public function toggleAdminStatus($id, bool $isAdmin)
    {
        $user = $this->find($id);
        $user->update(['is_admin' => $isAdmin]);

        return $user;
    }
}
