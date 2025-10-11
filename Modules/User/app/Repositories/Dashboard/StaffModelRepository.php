<?php

namespace Modules\User\Repositories\Dashboard;

use Modules\User\Models\User;

class StaffModelRepository implements StaffRepository
{
    public function index(?string $search = null): mixed
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        $query = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'staff'))
            ->whereHas('stores', fn ($q) => $q->where('stores.id', $store->id));

        if ($search) {
            return User::search($search)
                ->query(
                    fn ($builder) => $builder->whereHas('roles', fn ($q) => $q->where('name', 'staff'))
                        ->whereHas('stores', fn ($q) => $q->where('stores.id', $store->id))
                )
                ->paginate(10);
        }

        return $query->paginate(10);
    }

    public function create(array $data): User
    {
        return User::create($data)->assignRole('staff');
    }

    public function toggleActive(User $user, int $storeId): User
    {
        $pivot = $user->stores()->where('store_id', $storeId)->first()->pivot;
        $pivot->is_active = ! $pivot->is_active;
        $pivot->save();

        return $user;
    }
}
