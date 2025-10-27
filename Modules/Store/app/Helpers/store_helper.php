<?php


use Modules\Store\Models\Store;

if (! function_exists('current_store')) {
    function current_store()
    {
        $store = Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found'); // تجنب Server Error واظهار خطأ واضح
        }

        return $store;
    }
}
if (!function_exists('store_setting')) {
    function store_setting($key, $default = null)
    {
        $storeId = current_store()?->id;

        $settings = cache()->rememberForever("store_settings_{$storeId}", function () use ($storeId) {
            return \Modules\Store\Models\StoreSetting::where('store_id', $storeId)
                ->pluck('value', 'key')
                ->toArray();
        });

        return $settings[$key] ?? $default;
    }
}
