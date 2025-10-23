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
