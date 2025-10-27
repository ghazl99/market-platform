<?php

namespace Modules\Store\Services\Admin;

use Modules\Store\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Traits\TranslatableTrait;
use Illuminate\Validation\ValidationException;
use Modules\Store\Repositories\Admin\StoreRepository;

class StoreService
{
    use \Modules\Core\Traits\ImageTrait, TranslatableTrait;

    public function __construct(
        protected StoreRepository $storeRepository
    ) {}

    /**
     * Get stores owned by a given user.
     */
    public function index($user)
    {
        return $this->storeRepository->index($user);
    }

    /**
     * Create a new store and attach the given user.
     */
    public function store(array $data)
    {
        DB::beginTransaction();

        $user_id = $data['user_id'] ?? null;
        unset($data['user_id']);
        // Prepare translated fields
        $data = $this->prepareUserData($data);
        // Extract timezone into settings
        $settings = $data['settings'] ?? [];
        $settings['timezone'] = $data['timezone'] ?? config('app.timezone');
        $data['settings'] = $settings;
        // Create store
        $store = $this->storeRepository->create($data);
        if (isset($data['logo'])) {
            $this->uploadOrUpdateImageWithResize(
                $store,
                $data['logo'],
                'logo',
                'private_media',
                false
            );
        }

        if (isset($data['banner'])) {
            $this->uploadOrUpdateImageWithResize(
                $store,
                $data['banner'],
                'banner',
                'private_media',
                false
            );
        }
        // Attach user if provided
        $store->users()->attach($user_id, ['is_active' => true]);

        DB::commit();

        return $store;
    }

    public function update(array $data, $store)
    {
        DB::beginTransaction();
        $data = $this->prepareUserData($data);

        // Update store
        $this->storeRepository->update($data, $store);

        DB::commit();

        return $store;
    }

    public function delete($store)
    {
        DB::beginTransaction();

        // Delete store
        $this->storeRepository->delete($store);

        DB::commit();

        return true;
    }

    public function getSettings(Store $store): array
    {
        return $this->storeRepository->getSettings($store);
    }

    public function updateSettings(Store $store, array $data): bool
    {
        if (isset($data['logo']) && $data['logo']->isValid()) {
            $this->uploadOrUpdateImageWithResize(
                $store,
                $data['logo'],
                'logo',
                'private_media',
                true
            );
            unset($data['logo']);
        }

        if (isset($data['banners']) && is_array($data['banners'])) {
            $this->uploadOrUpdateImageWithResize(
                $store,
                $data['banners'],
                'banner',
                'private_media',
                true
            );
            unset($data['banners']);
        }

        // تحديث الإعدادات عبر الريبو
        return $this->storeRepository->updateSettings($store, $data);
    }
    /**
     * Prepare data for multilingual fields (name, description)
     */
    private function prepareUserData(array $data): array
    {
        $locale = app()->getLocale();
        $fields = ['name', 'description']; // fields to translate

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $translated = [$locale => $data[$field]];

                foreach ($this->otherLangs() as $lang) {
                    try {
                        $translated[$lang] = $this->autoGoogleTranslator($lang, $data[$field]);
                    } catch (\Exception $e) {
                        Log::error("Failed to translate [$field] to [$lang]: " . $e->getMessage());
                        $translated[$lang] = $data[$field]; // fallback to original
                    }
                }

                $data[$field] = $translated;
            }
        }

        return $data;
    }

    public function updateStoreStatus(int $id, string $status): string
    {
        $store = $this->storeRepository->findById($id);

        if (! $store) {
            throw ValidationException::withMessages(['store' => 'Store not found']);
        }

        if (! in_array($status, ['active', 'pending', 'inactive'])) {
            throw ValidationException::withMessages(['status' => 'Invalid status']);
        }

        $this->storeRepository->updateStatus($store, $status);

        return match ($status) {
            'active' => 'نشط',
            'pending' => 'قيد المراجعة',
            'inactive' => 'غير نشط',
        };
    }
}
