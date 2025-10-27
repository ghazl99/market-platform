<?php

namespace Modules\Store\Repositories\Admin;

use Modules\Store\Models\Store;
use Modules\Store\Models\StoreSetting;

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

    public function getSettings(Store $store): array
    {
        $settings = StoreSetting::where('store_id', $store->id)->get();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->value;
        }
        return $result;
    }

    public function updateSettings(Store $store, array $data): bool
    {
        foreach ($data as $key => $value) {
            if (is_null($value) || $value === '') {
                continue;
            }
            StoreSetting::updateOrCreate(
                ['store_id' => $store->id, 'key' => $key],
                ['value' => $value]
            );
        }
        cache()->forget("store_settings_{$store->id}");

        return true;
    }
}
