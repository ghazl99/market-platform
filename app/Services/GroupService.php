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

                try {
                    // حفظ النص الأصلي باللغة الحالية
                    $preparedData[$field] = [$locale => $original];

                    // ترجمة إلى جميع اللغات الأخرى
                    try {
                        $otherLangs = $this->otherLangs();
                        foreach ($otherLangs as $lang) {
                            try {
                                $preparedData[$field][$lang] = $this->autoGoogleTranslator($lang, $original);
                            } catch (\Exception $e) {
                                Log::warning("Translation failed for group [$field] to [$lang]: " . $e->getMessage());
                                $preparedData[$field][$lang] = $original; // Use original if translation fails
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning("Failed to get other languages for group translation: " . $e->getMessage());
                        // If translation fails completely, just use the original in current locale
                        $preparedData[$field] = [$locale => $original];
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to prepare group data for field [$field]: " . $e->getMessage());
                    // Fallback: use original as string if translation preparation fails
                    $preparedData[$field] = $original;
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
            Log::info('GroupService::create called with data:', $data);

            // تحضير الحقول المترجمة (فقط name)
            $translatedFields = $this->prepareGroupData($data);
            Log::info('Translated fields:', $translatedFields);

            // دمج البيانات: نضع البيانات الأساسية أولاً ثم الترجمات
            // هذا يضمن أن store_id و profit_percentage و is_default تُحفظ بشكل صحيح
            $groupData = array_merge($data, $translatedFields);
            Log::info('Merged data:', $groupData);

            // إضافة القيم الافتراضية
            $groupData['is_default'] = $groupData['is_default'] ?? false;

            // التأكد من أن store_id موجود (يجب أن يكون من Controller)
            if (!isset($groupData['store_id']) || empty($groupData['store_id'])) {
                Log::error('store_id is missing or empty in groupData', $groupData);
                throw new \Exception('store_id is required for group creation');
            }

            // تنظيف البيانات: إزالة أي حقول غير موجودة في fillable
            $fillable = ['name', 'profit_percentage', 'is_default', 'store_id'];
            $groupData = array_intersect_key($groupData, array_flip($fillable));

            Log::info('Final group data before create:', $groupData);

            $group = Group::create($groupData);

            // Refresh to get the latest data
            $group->refresh();

            Log::info('Group created successfully:', [
                'id' => $group->id,
                'store_id' => $group->store_id,
                'name' => $group->name,
                'profit_percentage' => $group->profit_percentage
            ]);

            DB::commit();

            return $group;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Group creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input_data' => $data,
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
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
            Log::info('GroupService::delete called', [
                'group_id' => $group->id,
                'group_name' => $group->name,
                'is_default' => $group->is_default,
            ]);

            // Check if this is the default group
            if ($group->is_default) {
                Log::warning('Attempted to delete default group', ['group_id' => $group->id]);
                throw new \Exception('Cannot delete the default group');
            }

            // Move users to default group before deleting
            $defaultGroup = Group::getDefaultGroup();
            if ($defaultGroup) {
                $usersCount = $group->users()->count();
                Log::info('Moving users to default group', [
                    'users_count' => $usersCount,
                    'default_group_id' => $defaultGroup->id,
                ]);

                if ($usersCount > 0) {
                    // Use DB facade to avoid model issues
                    DB::table('users')
                        ->where('group_id', $group->id)
                        ->update(['group_id' => $defaultGroup->id]);
                }

                Log::info('Users moved successfully', ['users_count' => $usersCount]);
            } else {
                Log::warning('No default group found, users will not be moved');
                // If no default group, set users group_id to null
                $usersCount = $group->users()->count();
                if ($usersCount > 0) {
                    DB::table('users')
                        ->where('group_id', $group->id)
                        ->update(['group_id' => null]);
                }
            }

            // Delete the group
            $groupId = $group->id;
            $group->delete();

            Log::info('Group deleted successfully', ['group_id' => $groupId]);

            DB::commit();

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Group deletion failed: ' . $e->getMessage(), [
                'group_id' => $group->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}

