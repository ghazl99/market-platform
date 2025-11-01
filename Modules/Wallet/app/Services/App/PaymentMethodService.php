<?php

namespace Modules\Wallet\Services\App;

use Modules\Store\Models\Store;
use Modules\Wallet\Repositories\App\PaymentMethodRepository;
use Modules\Core\Traits\TranslatableTrait;
use Illuminate\Support\Facades\Log;

class PaymentMethodService
{
    use TranslatableTrait;

    public function __construct(
        protected PaymentMethodRepository $paymentMethodRepository
    ) {}

    public function getForCurrentStore(Store $store)
    {
        return $this->paymentMethodRepository->getByStore($store);
    }

    /**
     * Prepare data for multilingual fields (name, recipient_name, bank_name, instructions)
     * Translate given fields to all supported languages automatically.
     *
     * @param  array  $data  Data array containing fields to translate
     * @param  array|null  $fields  Fields to translate, if null will translate all string fields
     * @return array Data with translated fields
     */
    public function prepareData(array $data, ?array $fields = null): array
    {
        $locale = app()->getLocale();

        // إذا لم يتم تحديد الحقول، استخدم الحقول الافتراضية
        if ($fields === null) {
            $fields = ['name', 'recipient_name', 'bank_name', 'instructions']; // الحقول الافتراضية للترجمة
        }

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                // إذا كان الحقل array بالفعل (multi-language)، استخرج النسخة الحالية
                if (is_array($data[$field])) {
                    $original = $data[$field][$locale] ?? reset($data[$field]);
                } else {
                    $original = $data[$field];
                }

                // ضمان أن يكون string
                if (!is_string($original)) {
                    $original = (string)($original ?? '');
                }

                // إذا كان النص فارغاً، تخطاه
                if (empty(trim($original))) {
                    continue;
                }

                // حفظ النص الأصلي باللغة الحالية
                $translated = [$locale => $original];

                // ترجمة إلى جميع اللغات الأخرى
                foreach ($this->otherLangs() as $lang) {
                    try {
                        // استخدام Google Translate للترجمة التلقائية
                        $translated[$lang] = $this->autoGoogleTranslator($lang, $original);
                    } catch (\Exception $e) {
                        Log::error("Failed to translate [$field] to [$lang]: " . $e->getMessage());
                        // في حالة فشل الترجمة، استخدم النص الأصلي كبديل
                        $translated[$lang] = $original;
                    }
                }

                // استبدال الحقل بنسخة متعددة اللغات
                $data[$field] = $translated;
            }
        }

        return $data;
    }
}
