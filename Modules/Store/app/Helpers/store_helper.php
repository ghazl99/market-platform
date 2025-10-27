<?php


use Modules\Store\Models\Store;
use Modules\Store\Models\StoreSetting;

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
        $store = current_store();
        $storeId = $store?->id;
        $themeId = $store?->theme_id;

        // 🟢 1. جلب إعدادات المتجر (إن وُجدت)
        $settings = StoreSetting::where('store_id', $storeId)
            ->pluck('value', 'key')
            ->toArray();

        // 🔹 إذا ما في إعدادات للمتجر، نجرب الثيم المخصص
        if (empty($settings) && $themeId) {
            $settings = StoreSetting::where('theme_id', $themeId)
                ->whereNull('store_id')
                ->pluck('value', 'key')
                ->toArray();
        }

        // 🔹 إذا ما في إعدادات للثيم، نجرب الإعدادات الافتراضية (theme_id و store_id فارغين)
        if (empty($settings)) {
            $settings = StoreSetting::whereNull('store_id')
                ->whereNull('theme_id')
                ->pluck('value', 'key')
                ->toArray();
        }

        return $settings[$key] ?? $default;
    }
}
if (!function_exists('current_theme_name_en')) {
    function current_theme_name_en()
    {
        $store = current_store();

        if (!$store || !$store->theme) {
            return 'default'; // قيمة افتراضية في حال لم يُعيّن ثيم
        }

        // استخدام Spatie للترجمة
        return $store->theme->getTranslation('name', 'en');
    }
}
