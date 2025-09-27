<?php

namespace Modules\Attribute\Services;

use Illuminate\Support\Facades\Log;
use Modules\Attribute\Repositories\AttributeRepository;
use Modules\Core\Traits\TranslatableTrait;

class AttributeService
{
    use \Modules\Core\Traits\ImageTrait, TranslatableTrait;

    public function __construct(
        protected AttributeRepository $attributeRepository,
    ) {}

    /**
     * Prepare data for multilingual fields (name, description)
     */
    private function prepareData(array $data): array
    {
        $locale = app()->getLocale();
        $fields = ['name']; // fields to translate

        foreach ($fields as $field) {
            if (isset($data[$field])) {

                // إذا كان array (multi-language) استخرج النسخة الحالية
                $original = is_array($data[$field]) ? ($data[$field][$locale] ?? reset($data[$field])) : $data[$field];

                $translated = [$locale => $original];

                foreach ($this->otherLangs() as $lang) {
                    try {
                        $translated[$lang] = $this->autoGoogleTranslator($lang, $original);
                    } catch (\Exception $e) {
                        Log::error("Failed to translate [$field] to [$lang]: ".$e->getMessage());
                        $translated[$lang] = $original; // fallback
                    }
                }

                $data[$field] = $translated;
            }
        }

        return $data;
    }

    public function getAllAttributes()
    {
        return $this->attributeRepository->index();
    }

    public function create(array $data)
    {
        $data = $this->prepareData($data);
        $locale = app()->getLocale();
        $name = $data['name'][$locale] ?? '';

        // تحقق من وجود الخاصية مسبقًا
        $attribute = $this->attributeRepository->findByName($name, $locale);

        if ($attribute) {
            return $attribute;
        }

        return $this->attributeRepository->create($data);
    }
}
