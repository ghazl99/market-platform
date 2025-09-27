<?php

namespace Modules\Store\Repositories\Admin;

use Modules\Store\Models\Store;

class StoreRepositoryModel implements StoreRepository
{
    public function index($user)
    {
        if ($user->hasRole('admin')) {
            return Store::with('owners')->latest()->get();
        }

        if ($user->hasRole('owner')) {
            return $user->ownedStores()->latest()->get();
        }

        return collect();
    }

    public function create(array $data): Store
    {
        return Store::create($data);
    }

    public function update(array $data, Store $store): bool
    {
        return $store->update($data);
    }

    public function delete(Store $store): bool
    {
        return $store->delete();
    }

    public function findById(int $id): ?Store
    {
        return Store::find($id);
    }

    public function updateStatus(Store $store, string $status): bool
    {
        return $store->update(['status' => $status]);
    }

    public function findStoreByDomain(string $domain): ?Store
    {
        return Store::where('domain', $domain)->first();
    }
}
