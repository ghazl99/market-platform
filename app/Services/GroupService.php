<?php

namespace App\Services;

use App\Group;
use Modules\Core\Traits\TranslatableTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GroupService
{
    use TranslatableTrait;

    /**
     * Prepare data for multilingual fields (name)
     * Translate given fields to all supported languages automatically.
     *
     * @param  array  $data  Data array containing fields to translate
     * @return array Data with translated fields
     */
    public function prepareGroupData(array $data): array
    {
        $locale = app()->getLocale();
        $fields = ['name'];
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
                        Log::warning("Translation failed for group [$field] to [$lang]: " . $e->getMessage());
                        $preparedData[$field][$lang] = $original;
                    }
                }
            }
        }

        return $preparedData;
    }

    /**
     * Create a new group with translation
     */
    public function create(array $data): Group
    {
        DB::beginTransaction();

        try {
            // تحضير الحقول المترجمة
            $translatedFields = $this->prepareGroupData($data);
            
            // دمج البيانات المترجمة مع البيانات الأخرى
            $groupData = array_merge($data, $translatedFields);

            // إضافة القيم الافتراضية
            $groupData['is_default'] = $groupData['is_default'] ?? false;
            $groupData['store_id'] = $groupData['store_id'] ?? null;

            $group = Group::create($groupData);

            DB::commit();

            return $group;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Group creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Update group with translation
     */
    public function update(Group $group, array $data): Group
    {
        DB::beginTransaction();

        try {
            // تحضير الحقول المترجمة
            $translatedFields = $this->prepareGroupData($data);
            
            // دمج البيانات المترجمة مع البيانات الأخرى
            $groupData = array_merge($data, $translatedFields);

            $group->update($groupData);

            DB::commit();

            return $group;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Group update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a group
     */
    public function delete(Group $group): bool
    {
        DB::beginTransaction();

        try {
            // Check if this is the default group
            if ($group->is_default) {
                throw new \Exception('Cannot delete the default group');
            }

            // Move users to default group before deleting
            $defaultGroup = Group::getDefaultGroup();
            if ($defaultGroup) {
                $group->users()->update(['group_id' => $defaultGroup->id]);
            }

            $group->delete();

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Group deletion failed: ' . $e->getMessage());
            throw $e;
        }
    }
}

