<?php


use Modules\Store\Models\Store;

if (! function_exists('current_store')) {
    function current_store()
    {
        return Store::currentFromUrl()->first();
    }
}
