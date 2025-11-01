<?php

namespace Modules\User\Services\Dashboard;

use Modules\User\Repositories\Dashboard\CustomerRepository;
use Modules\Core\Traits\TranslatableTrait;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    use TranslatableTrait;

    public function __construct(
        protected CustomerRepository $customerRepository
    ) {}

    public function getCustomers(?string $search = null)
    {
        return $this->customerRepository->index($search);
    }

    /**
     * Prepare data for multilingual fields (name, address, city)
     * Translate given fields to all supported languages automatically.
     *
     * @param  array  $data  Data array containing fields to translate
     * @return array Data with translated fields
     */
    public function prepareCustomerData(array $data): array
    {
        $locale = app()->getLocale();
        $fields = ['name', 'address', 'city'];
        $preparedData = [];

        foreach ($fields as $field) {
            if (isset($data[$field]) && !empty(trim($data[$field]))) {
                $original = trim($data[$field]);
                
                // حفظ النص الأصلي باللغة الحالية
                $preparedData[$field] = [$locale => $original];

                // ترجمة إلى جميع اللغات الأخرى
                foreach ($this->otherLangs() as $lang) {
                    try {
                        $preparedData[$field][$lang] = $this->autoGoogleTranslator($lang, $original);
                    } catch (\Exception $e) {
                        Log::warning("Translation failed for customer [$field] to [$lang]: " . $e->getMessage());
                        $preparedData[$field][$lang] = $original;
                    }
                }
            }
        }

        return $preparedData;
    }

    /**
     * Create a new customer with translation
     */
    public function createCustomer(array $data): array
    {
        // تحضير الحقول المترجمة
        $translatedFields = $this->prepareCustomerData($data);
        
        // دمج البيانات المترجمة مع البيانات الأخرى
        $customerData = array_merge($data, $translatedFields);

        return $customerData;
    }

    /**
     * Update customer with translation
     */
    public function updateCustomer(array $data): array
    {
        // تحضير الحقول المترجمة فقط للحقول التي تم تحديثها
        $translatedFields = $this->prepareCustomerData($data);
        
        // دمج البيانات المترجمة مع البيانات الأخرى
        $customerData = array_merge($data, $translatedFields);

        return $customerData;
    }
}
