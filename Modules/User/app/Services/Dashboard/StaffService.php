<?php

namespace Modules\User\Services\Dashboard;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\User\Models\User;
use Modules\User\Repositories\Dashboard\StaffRepository;
use Modules\Core\Traits\TranslatableTrait;
use Modules\Core\Traits\ImageTrait;

class StaffService
{
    use TranslatableTrait, ImageTrait;

    public function __construct(
        protected StaffRepository $staffRepository
    ) {}

    public function getStaffs(?string $search = null)
    {
        return $this->staffRepository->index($search);
    }

    /**
     * Prepare data for multilingual fields (name, address, city)
     * Translate given fields to all supported languages automatically.
     *
     * @param  array  $data  Data array containing fields to translate
     * @return array Data with translated fields
     */
    public function prepareStaffData(array $data): array
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
                        Log::warning("Translation failed for staff [$field] to [$lang]: " . $e->getMessage());
                        $preparedData[$field][$lang] = $original;
                    }
                }
            }
        }

        return $preparedData;
    }

    public function register(array $data)
    {
        $store = \Modules\Store\Models\Store::currentFromUrl()->first();

        if (! $store) {
            abort(404, 'Store not found');
        }

        // تحضير البيانات مع الترجمة
        $staffData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'staff',
        ];

        // إضافة الحقول الاختيارية إذا كانت موجودة
        if (isset($data['address'])) {
            $staffData['address'] = $data['address'];
        }
        if (isset($data['city'])) {
            $staffData['city'] = $data['city'];
        }
        if (isset($data['phone'])) {
            $staffData['phone'] = $data['phone'];
        }
        if (isset($data['birth_date'])) {
            $staffData['birth_date'] = $data['birth_date'];
        }
        if (isset($data['postal_code'])) {
            $staffData['postal_code'] = $data['postal_code'];
        }
        if (isset($data['country'])) {
            $staffData['country'] = $data['country'];
        }
        if (isset($data['language'])) {
            $staffData['language'] = $data['language'];
        }
        if (isset($data['timezone'])) {
            $staffData['timezone'] = $data['timezone'];
        }

        // تطبيق الترجمة على الحقول المترجمة
        $translatedFields = $this->prepareStaffData($staffData);
        $staffData = array_merge($staffData, $translatedFields);

        // Create user with hashed password
        $user = $this->staffRepository->create($staffData);
        $user->stores()->attach($store->id, ['is_active' => true]);

        // Handle profile photo upload if provided
        if (isset($data['profile_photo'])) {
            $this->uploadOrUpdateImageWithResize(
                $user,
                $data['profile_photo'],
                'profile_photo_images',
                'private_media',
                false
            );
        }

        $user->last_login_at = now();
        $user->save();

        // Return the created user instance
        return $user;
    }

    public function toggleActive(User $user, int $storeId): User
    {
        return $this->staffRepository->toggleActive($user, $storeId);
    }
}
