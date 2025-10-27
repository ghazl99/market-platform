<?php

namespace Modules\Store\Repositories\Admin;

use Modules\Store\Models\Store;

interface StoreRepository
{
    public function index($user);

    public function create(array $data): Store;

    public function update(array $data, Store $store): bool;

    public function delete(Store $store): bool;

    public function findById(int $id): ?Store;

    public function updateStatus(Store $store, string $status): bool;

    public function findStoreByDomain(string $domain): ?Store;

    public function getSettings(Store $store): array;

    public function updateSettings(Store $store, array $data): bool;

    public function createSettings($theme,array $data): bool;
}
