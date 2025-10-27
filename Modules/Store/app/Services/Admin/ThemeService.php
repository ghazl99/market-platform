<?php

namespace Modules\Store\Services\Admin;

use Modules\Core\Traits\TranslatableTrait;
use Modules\Store\Repositories\Admin\StoreRepository;
use Modules\Store\Repositories\Admin\ThemeRepository;

class ThemeService
{
    use  TranslatableTrait;

    public function __construct(
        protected ThemeRepository $themeRepository,
        protected StoreRepository $storeRepository

    ) {}

    public function getAllThemes()
    {
        return $this->themeRepository->all();
    }
    public function createTheme(array $data)
    {
        $data = $this->prepareThemeData($data);
        // dd($data);
        $theme = $this->themeRepository->create($data);

        // 3. إزالة الاسم من المصفوفة قبل تمرير الإعدادات
        $cleanData = collect($data)->except(['name'])->toArray();

        // 4. تخزين الإعدادات كافتراضية (بدون متجر)
        $this->storeRepository->createSettings($theme, $cleanData);

        return $theme;
    }
    private function prepareThemeData(array $data): array
    {
        $locale = app()->getLocale();
        $fields = ['name']; // fields to translate

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $translated = [$locale => $data[$field]];

                foreach ($this->otherLangs() as $lang) {
                    try {
                        $translated[$lang] = $this->autoGoogleTranslator($lang, $data[$field]);
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                        $translated[$lang] = $data[$field]; // fallback to original
                    }
                }

                $data[$field] = $translated;
            }
        }

        return $data;
    }
}
