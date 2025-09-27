<?php

namespace Modules\User\Repositories\Dashboard;

use Modules\User\Models\User;

class CustomerModelRepository implements CustomerRepository
{
    public function index(?string $search = null): mixed
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        $query = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'customer'))
            ->whereHas('stores', fn ($q) => $q->where('stores.id', $store->id));

        if ($search) {
            return User::search($search)
                ->query(
                    fn ($builder) => $builder->whereHas('roles', fn ($q) => $q->where('name', 'customer'))
                        ->whereHas('stores', fn ($q) => $q->where('stores.id', $store->id))
                )
                ->paginate(10);
        }

        return $query->paginate(10);
    }
}
